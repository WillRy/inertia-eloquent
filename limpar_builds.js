const path = require("path");
const fs = require("fs");
/**
 * Laravel Mix Clean.
 */
class Clean {
    boot(){
        var folder = './public/js/';

        fs.readdir(folder, (err, files) => {
            if (err) throw err;

            for (const file of files) {
                fs.unlinkSync(folder+file);
            }

        });
    }
    /**
     * Dependencies for plugin.
     *
     * @return {String[]}
     */
    dependencies() {

    }

    /**
     * Plugin functionality.
     *
     * @param options
     */
    register(options = {}) {
        this.options = options
    }

    /*
     * Plugins to be merged with the master webpack config.
     *
     * @return {Array|Object}
     */
    webpackPlugins() {
    }
}

module.exports = Clean;
