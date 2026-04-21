const express = require("express");
const app = express();

app.get("/", (req, res) => {
  res.send("Funciona");
});

const port = process.env.PORT || 3000;

app.listen(port, () => {
  console.log("Servidor listo");
});

app.use((err, req, res, next) => {
  res.status(500).send("Error");
});