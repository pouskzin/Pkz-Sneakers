// Arquivo: bot_discord/bot.js
const { Client, GatewayIntentBits, EmbedBuilder, ChannelType, PermissionsBitField } = require('discord.js');
const mysql = require('mysql2/promise');
require('dotenv').config();

// ================= CONFIGURAÇÕES =================
const DISCORD_TOKEN = 'TOKEN_DO_DISCORD_AQUI'; 
const SERVIDOR_ID = 'ID_DO_SERVIDOR_AQUI';

const DB_CONFIG = {
    host: 'localhost',
    user: 'root',
    password: '',
    database: '',
    port: // Confirme sua porta
};
// =================================================

const client = new Client({
    intents: [
        GatewayIntentBits.Guilds,
        GatewayIntentBits.GuildMessages,
        GatewayIntentBits.MessageContent
    ]
});

let connection;
let processando = false; // TRAVA DE SEGURANÇA: Evita que o bot tente processar a mesma coisa duas vezes ao mesmo tempo

async function conectarBanco() {
    try {
        connection = await mysql.createConnection(DB_CONFIG);
        console.log('✅ Conectado ao Banco de Dados!');
    } catch (error) {
        console.error('❌ Erro ao conectar no Banco:', error.message);
    }
}

async function gerenciarCategorias(guild) {
    const categorias = guild.channels.cache.filter(c => 
        c.type === ChannelType.GuildCategory && c.name.startsWith('Clientes #')
    );

    const categoriasOrdenadas = categorias.sort((a, b) => {
        const numA = parseInt(a.name.split('#')[1] || 0);
        const numB = parseInt(b.name.split('#')[1] || 0);
        return numA - numB;
    });

    let categoriaAlvo = categoriasOrdenadas.last();

    // Se não existir ou estiver cheia (50 canais), cria nova
    if (!categoriaAlvo || categoriaAlvo.children.cache.size >= 50) {
        let proximoNumero = 1;
        if (categoriaAlvo) {
            proximoNumero = parseInt(categoriaAlvo.name.split('#')[1] || 0) + 1;
        }

        console.log(`📂 Criando nova categoria: Clientes #${proximoNumero}`);
        
        categoriaAlvo = await guild.channels.create({
            name: `Clientes #${proximoNumero}`,
            type: ChannelType.GuildCategory,
            permissionOverwrites: [
                {
                    id: guild.id, 
                    deny: [PermissionsBitField.Flags.ViewChannel],
                },
            ],
        });
    }

    return categoriaAlvo;
}

async function verificarNovasMensagens() {
    // Se não tiver banco ou já estiver trabalhando, não faz nada
    if (!connection || processando) return;

    processando = true; // Liga a trava

    try {
        const [rows] = await connection.execute(
            'SELECT * FROM mensagens_contato WHERE status_envio = 0 ORDER BY id ASC'
        );

        if (rows.length > 0) {
            console.log(`📨 Processando ${rows.length} mensagens pendentes...`);
            
            const guild = await client.guilds.fetch(SERVIDOR_ID);
            if (!guild) {
                console.error("❌ Servidor não encontrado!");
                return;
            }

            for (const msg of rows) {
                // Define o nome padrão do canal
                const nomeCanal = `${msg.nome.split(' ')[0].toLowerCase()}-${msg.id}`;
                
                // 1. VERIFICAÇÃO DE DUPLICIDADE (A Correção do Erro)
                // Procura em TODOS os canais do servidor se já existe um com esse nome
                const canalExistente = guild.channels.cache.find(c => c.name === nomeCanal);

                if (canalExistente) {
                    console.log(`⚠️ O canal ${nomeCanal} já existe! Apenas atualizando o banco...`);
                    
                    // Apenas marca como enviado no banco para não tentar de novo
                    await connection.execute(
                        'UPDATE mensagens_contato SET status_envio = 1 WHERE id = ?', 
                        [msg.id]
                    );
                    continue; // Pula para a próxima mensagem
                }

                // Se não existe, segue o fluxo normal...
                const categoria = await gerenciarCategorias(guild);

                const canal = await guild.channels.create({
                    name: nomeCanal,
                    type: ChannelType.GuildText,
                    parent: categoria.id,
                    topic: `Email: ${msg.email} | Tel: ${msg.telefone}`
                });

                const embed = new EmbedBuilder()
                    .setColor(0x0099FF)
                    .setTitle(`Novo Cliente: ${msg.nome}`)
                    .addFields(
                        { name: '📧 Email', value: msg.email, inline: true },
                        { name: '📱 Telefone', value: msg.telefone || 'N/A', inline: true },
                        { name: '📝 Mensagem', value: msg.mensagem }
                    )
                    .setTimestamp(msg.data_criacao)
                    .setFooter({ text: `Protocolo: ${msg.id}` });

                await canal.send({ content: "||@here|| Novo contato!", embeds: [embed] });

                await connection.execute(
                    'UPDATE mensagens_contato SET status_envio = 1 WHERE id = ?', 
                    [msg.id]
                );
                
                console.log(`✅ Canal criado: ${canal.name}`);
            }
        }
    } catch (error) {
        console.error('Erro no processamento:', error);
        if (error.code === 'PROTOCOL_CONNECTION_LOST') await conectarBanco();
    } finally {
        processando = false; // Destrava para a próxima rodada
    }
}

client.once('ready', async () => {
    console.log(`🤖 Bot ${client.user.tag} ONLINE e protegido contra duplicidade!`);
    await conectarBanco();
    setInterval(verificarNovasMensagens, 5000);
});

client.login(DISCORD_TOKEN);
