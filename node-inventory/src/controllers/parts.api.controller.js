const { validationResult } = require('express-validator');
const repo = require('../repositories/parts.repository');

function formatValidationErrors(req) {
  const errors = validationResult(req);
  return errors.isEmpty() ? [] : errors.array().map((error) => error.msg);
}

async function index(req, res, next) {
  try {
    const query = (req.query.q || '').trim();
    const page = Math.max(1, parseInt(req.query.page, 10) || 1);
    const perPage = Math.max(1, Math.min(50, parseInt(req.query.per_page, 10) || 9));

    const { items, total } = await repo.findPartsPaginated({ query, page, perPage });
    const totalPages = Math.max(1, Math.ceil(total / perPage));

    return res.status(200).json({
      message: 'Pecas listadas com sucesso.',
      data: {
        items,
        meta: {
          query,
          page,
          per_page: perPage,
          total,
          total_pages: totalPages,
          has_prev: page > 1,
          has_next: page < totalPages,
        },
      },
      errors: [],
    });
  } catch (error) {
    return next(error);
  }
}

async function show(req, res, next) {
  try {
    const part = await repo.findPartById(req.params.id);

    if (!part) {
      return res.status(404).json({
        message: 'Peca nao encontrada.',
        data: null,
        errors: [],
      });
    }

    return res.status(200).json({
      message: 'Peca encontrada.',
      data: part,
      errors: [],
    });
  } catch (error) {
    return next(error);
  }
}

async function store(req, res, next) {
  const errors = formatValidationErrors(req);

  if (errors.length) {
    return res.status(422).json({
      message: 'Dados invalidos.',
      data: null,
      errors,
    });
  }

  try {
    const newId = await repo.createPart(req.body);
    const created = await repo.findPartById(newId);

    return res.status(201).json({
      message: 'Peca criada com sucesso.',
      data: created,
      errors: [],
    });
  } catch (error) {
    if (error.code === 'ER_DUP_ENTRY') {
      return res.status(409).json({
        message: 'Ja existe uma peca com este nome.',
        data: null,
        errors: ['Nome da peca deve ser unico.'],
      });
    }

    return next(error);
  }
}

async function update(req, res, next) {
  const errors = formatValidationErrors(req);

  if (errors.length) {
    return res.status(422).json({
      message: 'Dados invalidos.',
      data: null,
      errors,
    });
  }

  try {
    const affected = await repo.updatePart(req.params.id, req.body);

    if (!affected) {
      return res.status(404).json({
        message: 'Peca nao encontrada.',
        data: null,
        errors: [],
      });
    }

    const updated = await repo.findPartById(req.params.id);

    return res.status(200).json({
      message: 'Peca atualizada com sucesso.',
      data: updated,
      errors: [],
    });
  } catch (error) {
    if (error.code === 'ER_DUP_ENTRY') {
      return res.status(409).json({
        message: 'Ja existe uma peca com este nome.',
        data: null,
        errors: ['Nome da peca deve ser unico.'],
      });
    }

    return next(error);
  }
}

async function destroy(req, res, next) {
  try {
    const affected = await repo.deletePart(req.params.id);

    if (!affected) {
      return res.status(404).json({
        message: 'Peca nao encontrada.',
        data: null,
        errors: [],
      });
    }

    return res.status(200).json({
      message: 'Peca removida com sucesso.',
      data: { id: Number(req.params.id) },
      errors: [],
    });
  } catch (error) {
    return next(error);
  }
}

async function findPartsPaginated(req, res, next) {
  try {
    const parts = await repo.findPartsPaginated(req.params);
    return res.status(200).json({
      message: 'Pecas listadas com sucesso.',
      data: parts,
      errors: [],
    });
  } catch (error) {
    return next(error);
  }
}

module.exports = {
  index,
  show,
  store,
  update,
  destroy,
  findPartsPaginated,
};
