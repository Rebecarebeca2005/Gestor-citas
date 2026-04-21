const express = require("express");
const path = require("path");

const app = express();

// Archivos estáticos (CSS, JS, imágenes)
app.use(express.static(path.join(__dirname, "public")));

// Página principal
app.get("/", (req, res) => {
  res.sendFile(path.join(__dirname, "views", "home.html"));
});

// Puerto Railway
const port = process.env.PORT || 3000;

app.listen(port, () => {
  console.log("Servidor en puerto " + port);
});