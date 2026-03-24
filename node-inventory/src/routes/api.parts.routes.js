const express = require('express');
const { body, param } = require('express-validator');
const { validationResult } = require('express-validator');
const controller = require('../controllers/parts.api.controller');

const router = express.Router();

const idValidation = param('id')
  .isInt({ min: 1 })
  .withMessage('ID da peca invalido.');

const partValidations = [
  body('nome_peca')
    .trim()
    .notEmpty()
    .withMessage('Nome da peca e obrigatorio.')
    .isLength({ max: 255 })
    .withMessage('Nome da peca deve ter no maximo 255 caracteres.'),
  body('preco_custo')
    .isFloat({ min: 0 })
    .withMessage('Preco de custo deve ser um numero maior ou igual a zero.'),
  body('preco_venda')
    .isFloat({ min: 0 })
    .withMessage('Preco de venda deve ser um numero maior ou igual a zero.'),
  body('quantidade_disponivel')
    .isInt({ min: 0 })
    .withMessage('Quantidade disponivel deve ser um inteiro maior ou igual a zero.'),
];

function validationGuard(req, res, next) {
  const errors = validationResult(req);

  if (errors.isEmpty()) {
    return next();
  }

  return res.status(422).json({
    message: 'Dados invalidos.',
    data: null,
    errors: errors.array().map((error) => error.msg),
  });
}

router.get('/parts', controller.index);
router.get('/parts/:id', [idValidation, validationGuard], controller.show);
router.post('/parts', [...partValidations, validationGuard], controller.store);
router.put('/parts/:id', [idValidation, ...partValidations, validationGuard], controller.update);
router.delete('/parts/:id', [idValidation, validationGuard], controller.destroy);

module.exports = router;
