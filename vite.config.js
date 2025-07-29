import { defineConfig } from "vite";
import { terser } from "rollup-plugin-terser";
import { resolve } from "path";

export default defineConfig({
  root: "./",
  base: "./",
  build: {
    minify: "terser",
    rollupOptions: {
      input: {
        main: resolve(__dirname, "index.html"),
        obrigado: resolve(__dirname, "obrigado.html"),
        maquinasAgricolas: resolve(__dirname, "maquinas-agricolas.html"),
      },
      output: {
        plugins: [terser()],
      },
    },
  },
});
