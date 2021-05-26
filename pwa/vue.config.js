module.exports = {
  chainWebpack: (config) => {
    config.plugins.delete("pwa");
    config.plugins.delete("workbox");
  },
};
