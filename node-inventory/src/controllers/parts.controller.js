const { validationResult } = require('express-validator');
const repo = require('../repositories/parts.repository');

function baseViewData(overrides = {}) {
  return {
    title: 'Estoque 3D',
    errors: [],
    formData: {
      nome_peca: '',
      preco_custo: '',
      preco_venda: '',
      quantidade_disponivel: '',
    },
    message: null,
    ...overrides,
  };
}

async function listParts(req, res, next) {
  try {
    const parts = await repo.findAllParts();
    res.render('parts/index', baseViewData({
      title: 'Pecas em Estoque',
      parts,
      message: req.query.message || null,
    }));
  } catch (error) {
    next(error);
  }
}

function newPartForm(req, res) {
  res.render('parts/form', baseViewData({
    title: 'Nova Peca',
    mode: 'create',
    submitLabel: 'Salvar peca',
  }));
}

async function createPart(req, res, next) {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.status(422).render('parts/form', baseViewData({
      title: 'Nova Peca',
      mode: 'create',
      submitLabel: 'Salvar peca',
      errors: errors.array(),
      formData: req.body,
    }));
  }

  try {
    await repo.createPart(req.body);
    res.redirect('/parts?message=Peca criada com sucesso');
  } catch (error) {
    if (error.code === 'ER_DUP_ENTRY') {
      return res.status(409).render('parts/form', baseViewData({
        title: 'Nova Peca',
        mode: 'create',
        submitLabel: 'Salvar peca',
        errors: [{ msg: 'Ja existe uma peca com este nome.' }],
        formData: req.body,
      }));
    }
    next(error);
  }
}

async function editPartForm(req, res, next) {
  try {
    const part = await repo.findPartById(req.params.id);
    if (!part) {
      return res.status(404).send('Peca nao encontrada.');
    }

    res.render('parts/form', baseViewData({
      title: 'Editar Peca',
      mode: 'edit',
      submitLabel: 'Atualizar peca',
      partId: part.id,
      formData: part,
    }));
  } catch (error) {
    next(error);
  }
}

async function updatePart(req, res, next) {
  const errors = validationResult(req);
  const partId = Number(req.params.id);

  if (!errors.isEmpty()) {
    return res.status(422).render('parts/form', baseViewData({
      title: 'Editar Peca',
      mode: 'edit',
      submitLabel: 'Atualizar peca',
      partId,
      errors: errors.array(),
      formData: { ...req.body, id: partId },
    }));
  }

  try {
    const affected = await repo.updatePart(partId, req.body);
    if (!affected) {
      return res.status(404).send('Peca nao encontrada.');
    }
    res.redirect('/parts?message=Peca atualizada com sucesso');
  } catch (error) {
    if (error.code === 'ER_DUP_ENTRY') {
      return res.status(409).render('parts/form', baseViewData({
        title: 'Editar Peca',
        mode: 'edit',
        submitLabel: 'Atualizar peca',
        partId,
        errors: [{ msg: 'Ja existe uma peca com este nome.' }],
        formData: { ...req.body, id: partId },
      }));
    }
    next(error);
  }
}

async function deletePart(req, res, next) {
  try {
    await repo.deletePart(req.params.id);
    res.redirect('/parts?message=Peca removida com sucesso');
  } catch (error) {
    next(error);
  }
}

module.exports = {
  listParts,
  newPartForm,
  createPart,
  editPartForm,
  updatePart,
  deletePart,
};
