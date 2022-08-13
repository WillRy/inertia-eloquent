import moment from 'moment-timezone';
import mitt from 'mitt'

let LaravelError = {
    install: (app, options) => {
        // inject a globally available $translate() method
        app.config.globalProperties.$laravelError = (e, message) => {
            console.log(app)
            let response = e.response;
            if (response && response.status === 422 && response.data.errors) {
                let erro = Object.keys(response.data.errors)[0];
                app.config.globalProperties.$toast.open({
                    message: response.data.errors[erro][0],
                    type: 'error'
                });
            } else if (response && response.data.error) {
                app.config.globalProperties.$toast.open({
                    message: response.data.error,
                    type: 'error'
                });
            } else {
                app.config.globalProperties.$toast.open({
                    message: message,
                    type: 'error'
                });
            }
        }
    }
}

let filters = {
    install: (app, options) => {
        app.config.globalProperties.$filters = {
            data(value) {
                if(value === '0000-00-00 00:00:00') return ''
                return value ? moment(value).format("DD/MM/Y") : '';
            },
            dataHora(value) {
                if(value === '0000-00-00 00:00:00') return ''
                return value ? moment(value).format("DD/MM/Y HH:mm:ss") : '';
            },
            dinheiro(valor) {
                return (new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(valor));
            },
            genero(valor) {
                switch (valor) {
                    case 'm':
                        return 'masculino'
                    case 'f':
                        return 'feminino'
                    default:
                        return 'outro'
                }
            }
        }
    }
}


let EventBus = {
    install: (app, options) => {
        const emitter = mitt();
        app.config.globalProperties.$eventBus = {
            $on: (...args) => emitter.on(...args),
            $once: (...args) => emitter.once(...args),
            $off: (...args) => emitter.off(...args),
            $emit: (...args) => emitter.emit(...args),
        }
    }
}


let VerificaPermissao = {
    install: (app, options) => {
        app.config.globalProperties.$verificaPermissao = ($page, permissao) => {
            return $page.props.auth.permissoes.find(
                (item) => item === permissao
            );
        }
    }
}

export {
    filters,
    LaravelError,
    EventBus,
    VerificaPermissao
}
