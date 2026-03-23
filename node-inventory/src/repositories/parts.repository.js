const { pool } = require('../config/db');

async function findAllParts() {
  const [rows] = await pool.query('SELECT id, nome_peca, preco_custo, preco_venda, quantidade_disponivel FROM parts ORDER BY id DESC');
  return rows;
}

async function findPartById(id) {
  const [rows] = await pool.query('SELECT id, nome_peca, preco_custo, preco_venda, quantidade_disponivel FROM parts WHERE id = ?', [id]);
  return rows[0] || null;
}

async function createPart(data) {
  const { nome_peca, preco_custo, preco_venda, quantidade_disponivel } = data;
  const [result] = await pool.query(
    'INSERT INTO parts (nome_peca, preco_custo, preco_venda, quantidade_disponivel) VALUES (?, ?, ?, ?)',
    [nome_peca, preco_custo, preco_venda, quantidade_disponivel]
  );
  return result.insertId;
}

async function updatePart(id, data) {
  const { nome_peca, preco_custo, preco_venda, quantidade_disponivel } = data;
  const [result] = await pool.query(
    'UPDATE parts SET nome_peca = ?, preco_custo = ?, preco_venda = ?, quantidade_disponivel = ? WHERE id = ?',
    [nome_peca, preco_custo, preco_venda, quantidade_disponivel, id]
  );
  return result.affectedRows;
}

async function deletePart(id) {
  const [result] = await pool.query('DELETE FROM parts WHERE id = ?', [id]);
  return result.affectedRows;
}

async function findPartsPaginated({ query, page, perPage }) {
  const offset = (page - 1) * perPage;
  let whereClause = '';
  const params = [];

  if (query && query.trim() !== '') {
    whereClause = 'WHERE nome_peca LIKE ?';
    params.push(`%${query}%`);
  }

  const [[{ total }]] = await pool.query(
    `SELECT COUNT(*) AS total FROM parts ${whereClause}`,
    params
  );

  const [items] = await pool.query(
    `SELECT id, nome_peca, preco_custo, preco_venda, quantidade_disponivel
     FROM parts ${whereClause}
     ORDER BY id DESC
     LIMIT ? OFFSET ?`,
    [...params, perPage, offset]
  );

  return { items, total };
}

module.exports = {
  findAllParts,
  findPartById,
  createPart,
  updatePart,
  deletePart,
  findPartsPaginated,
};
