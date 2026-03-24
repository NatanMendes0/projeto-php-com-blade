function apiKeyAuth(req, res, next) {
  const expectedKey = process.env.NODE_API_KEY;

  if (!expectedKey) {
    return res.status(500).json({
      message: 'NODE_API_KEY nao configurada no servidor.',
      data: null,
      errors: [],
    });
  }

  const providedKey = req.header('x-api-key');

  if (!providedKey || providedKey !== expectedKey) {
    return res.status(401).json({
      message: 'Nao autorizado.',
      data: null,
      errors: ['API key invalida ou ausente.'],
    });
  }

  next();
}

module.exports = apiKeyAuth;
