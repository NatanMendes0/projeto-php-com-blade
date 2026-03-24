const express = require('express');
const { body, param } = require('express-validator');
const controller = require('../controllers/parts.controller');

const router = express.Router();

const partValidations = [
  body('nome_peca').trim().notEmpty().withMessage('Nome da peca e obrigatorio.').isLength({ max: 255 }).withMessage('Nome da peca deve ter no maximo 255 caracteres.'),
  body('preco_custo').isFloat({ min: 0 }).withMessage('Preco de custo deve ser um numero maior ou igual a zero.'),
  body('preco_venda').isFloat({ min: 0 }).withMessage('Preco de venda deve ser um numero maior ou igual a zero.'),
  body('quantidade_disponivel').isInt({ min: 0 }).withMessage('Quantidade disponivel deve ser um inteiro maior ou igual a zero.'),
];

router.get('/', controller.listParts);
router.get('/new', controller.newPartForm);
router.post('/', partValidations, controller.createPart);
router.get('/:id/edit', param('id').isInt({ min: 1 }), controller.editPartForm);
router.put('/:id', [param('id').isInt({ min: 1 }), ...partValidations], controller.updatePart);
router.delete('/:id', param('id').isInt({ min: 1 }), controller.deletePart);

module.exports = router;
