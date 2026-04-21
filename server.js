const express = require("express");
const app = express();

app.get("/", (req, res) => {
  res.send("FUNCIONA");
});

app.get("/test", (req, res) => {
  res.send("TEST OK");
});

app.use((err, req, res, next) => {
  console.error(err);
  res.status(500).send("Error interno");
});

const port = process.env.PORT || 3000;
app.listen(port, () => {
  console.log("Servidor en puerto " + port);
});