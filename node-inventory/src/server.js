const path = require('path');
const express = require('express');
const methodOverride = require('method-override');
const dotenv = require('dotenv');
const { initializeDatabase } = require('./config/db');
const partsRoutes = require('./routes/parts.routes');
const apiPartsRoutes = require('./routes/api.parts.routes');
const apiKeyAuth = require('./middlewares/apiKeyAuth');

dotenv.config();

const app = express();
const port = process.env.APP_PORT || 3000;

app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

app.use(express.urlencoded({ extended: true }));
app.use(express.json());
app.use(methodOverride('_method'));
app.use(express.static(path.join(__dirname, 'public')));

app.get('/', (req, res) => {
  res.redirect('/parts');
});

app.use('/parts', partsRoutes);
app.use('/api/v1', apiKeyAuth, apiPartsRoutes);

app.use((err, req, res, next) => {
  if (req.path.startsWith('/api/')) {
    return res.status(500).json({
      message: 'Erro interno no servidor.',
      data: null,
      errors: [err.message],
    });
  }

  // Mantem resposta previsivel para erros nao tratados em rotas web.
  console.error(err);
  res.status(500).send('Erro interno no servidor.');
});

(async () => {
  try {
    await initializeDatabase();
    app.listen(port, () => {
      console.log(`Node inventory rodando em http://localhost:${port}`);
    });
  } catch (error) {
    console.error('Falha ao iniciar aplicacao Node:', error.message);
    process.exit(1);
  }
})();
