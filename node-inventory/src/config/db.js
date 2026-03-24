const mysql = require('mysql2/promise');

const pool = mysql.createPool({
  host: process.env.DB_HOST || 'mysql',
  port: Number(process.env.DB_PORT || 3306),
  user: process.env.DB_USER || 'laravel',
  password: process.env.DB_PASSWORD || 'secret',
  database: process.env.DB_NAME || 'laravel',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0,
});

async function initializeDatabase() {
  await pool.query(`
    CREATE TABLE IF NOT EXISTS parts (
      id INT UNSIGNED NOT NULL AUTO_INCREMENT,
      nome_peca VARCHAR(255) NOT NULL,
      preco_custo DECIMAL(10,2) NOT NULL,
      preco_venda DECIMAL(10,2) NOT NULL,
      quantidade_disponivel INT NOT NULL DEFAULT 0,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (id),
      UNIQUE KEY uq_parts_nome_peca (nome_peca)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
  `);
}

module.exports = {
  pool,
  initializeDatabase,
};
