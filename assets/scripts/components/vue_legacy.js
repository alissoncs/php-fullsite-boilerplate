var Vue = require('vue/dist/vue');

var vueSelect = require('vue-select/dist/vue-select').VueSelect;

Vue.component('v-select', vueSelect);


Vue.component('v-button-counter', {
  data: function () {
    return {
      count: 0,
    };
  },
  template:
    '<button type="button" v-on:click="count++">Você clicou em mim {{ count }} vezes.</button>',
});

Vue.component('v-pretty-toggle', {
  props: ['name'],
  data: function () {
    return {
      checked: false,
    };
  },
  methods: {
    toggle: function () {
      this.checked = !this.checked;
    },
  },
  template: `<span class="pretty-toggle"
  v-bind:data-name='name'
  v-bind:class="{ active: checked }"
  v-bind:aria-checked="checked ? 'true' : 'false'"
  role='checkbox' v-on:click="toggle">
    <i class="on">On</i>
    <i class="off">Off</i>
    <input type='hidden' v-bind:value="checked ? 'true' : 'false'" v-bind:name='name' />
  </span>`,
});



Vue.component('doctor-searcher', {
  template: '#doctor-searcher-template',
  delimiters: ['${', '}'],
  data: function () {
    return {
      type: 'advanced',
      submitting: false,

      termo: null,
      estado: null,
      cidade: null,
      plano: null,
      bairro: null,
      especializacao: null,
      recurso: null,
      documento: null,
      qualificacao: null,
      rede: null,

      loadingEstados: false,

      listPlanos: [],
      listEstados: [],
    };
  },
  mounted: function () {
    var self = this;
    $.get(BASE_API_URL + '/medicos/metadata/planos', function (data) {
      self.listPlanos = data.data.map(function (item) {
        return {
          label: item.descricao,
          value: item.codigo_legado,
        };
      });
    });
  },
  methods: {
    changePlano: function (value) {
      this.loadingEstados = true;
      var self = this;
      $.get(BASE_API_URL + '/medicos/metadata/estados', {
        'id_plano': this.plano && this.plano.value
      }, function (data) {
        self.loadingEstados = false;
        self.listEstados = data && data.data.map(function (item) {
          return {
            label: item.nome,
            value: item.uf,
            id: item.id,
          };
        });
      });
    },
    changeEstado: function (value) {
      this.loadingCidades = true;
      var self = this;
      $.get(BASE_API_URL + '/medicos/metadata/cidades', {
        'id_plano': this.plano && this.plano.value,
        'id_estados': this.estado && this.estado.value,
      }, function (data) {
        self.loadingCidades = false;
        self.listCidades = data && data.data.map(function (item) {
          return {
            label: item.nome,
            value: item.id,
          };
        });
      });
    },
    submitForm: function (event) {
      this.submitting = true;
    },
  }
});

Vue.component('form-newsletter', {
  template: '#form-newsletter-template',
  delimiters: ['${', '}'],
  data: function () {
    return {
      nome: '',
      email: '',
      sucesso: null,
      erro: null,
      erroNome: null,
      erroEmail: null,
      submitting: false,
    };
  },
  methods: {
    submitForm: function (event) {
      event.preventDefault();
      this.submitting = true;

      if (!this.nome) {
        this.erroNome = true;
      }
      if (!this.email) {
        this.erroEmail = true;
      }
      var url = event.target.getAttribute('action');
      var self = this;

      $.post(url, {
        'nome': this.nome,
        'email': this.email,
      }, function () {
        self.submitting = false;
        self.sucesso = true;
        self.nome = '';
        self.email = '';
      });

    },
  }
});

Vue.component('fale-conosco', {
  template: '#fale-conosco-template',
  delimiters: ['${', '}'],
  data: function () {
    return {
      title: null,
      icon: null,

      nome: '',
      email: '',
      telefone: '',
      mensagem: '',

      enviando: false,
      enviada: false,
      erro: false,
    };
  },
  methods: {
    yourCallBackFunction: function (event) {
      alert('back');
    },
    clickItem: function (title, icon, event) {
      if (event) event.preventDefault();
      this.title = title;
      this.icon = icon;
    },
    closeForm: function (event) {
      event.preventDefault();
      this.title = null;
      this.icon = null;
      this.enviada = false;
      this.erro = false;
      this.enviando = false;
      this.clearData();
    },
    clearData: function () {
      this.nome = '';
      this.email = '';
      this.telefone = '';
      this.mensagem = '';
    },
    submitForm: function (event) {
      event.preventDefault();
      this.enviando = true;
      this.enviada = false;
      this.erro = false;
      var self = this;
      $.post(event.target.getAttribute('action'), {
        assunto: this.title,
        nome: this.nome,
        telefone: this.telefone,
        email: this.email,
        mensagem: this.mensagem,
      }, function (err, data) {
        self.enviando = false;
        if (!err) {
          self.clearData();
          self.enviada = true;
        } else {
          self.erro = true;
        }
      });
    },
  },
  created: function () {
    document.addEventListener("backbutton", this.yourCallBackFunction, false);
  },
  beforeDestroy: function () {
    document.removeEventListener("backbutton", this.yourCallBackFunction);
  }
});

Vue.component('imc', {
  template: '#imc-template',
  delimiters: ['${', '}'],
  data: function () {
    return {
      altura: '',
      peso: '',
      calculando: false,
      resultado: '',
      type: null,
      color: 'normal',
    };
  },
  methods: {
    submitForm: function (event) {
      event.preventDefault();
      var alturaInt = parseInt(this.altura, 10);
      var pesoInt = parseInt(this.peso, 10);

      if (pesoInt < 30 || pesoInt > 200) {
        this.erro = 'Peso inválido';
        return;
      }
      if (alturaInt < 60 || alturaInt > 230) {
        this.erro = 'Altura inválida';
        return;
      }

      this.calculando = true;
      var self = this;

      setTimeout(function () {
        var result = pesoInt / ((alturaInt / 100) * (alturaInt / 100));
        result = result && result.toFixed(2);

        self.calculando = false;
        self.resultado = result;

        if (result < 18.5) {
          self.type = 'Abaixo do peso';
          self.color = 'danger';
        } else if (result >= 18.5 && result <= 24.9) {
          self.type = 'Peso normal';
          self.color = 'normal';
        } else if (result > 25) {
          self.type = 'Sobre peso';
          self.color = 'danger';
        }

      }, 800);
    },
  }
});


Vue.component('module-tree', {
  template: '#module-tree-template',
  delimiters: ['${', '}'],
  data: function () {
    return {
    };
  },
  methods: {
    hoverItem: function (id) {
      alert('ok//' + id);
    }
  }
});

new Vue({
  el: '#vue-app',
  delimiters: ['${', '}'],
  color: '#fafafa',
  data: function () {
    return {
      isMobile: window.matchMedia && !window.matchMedia("(min-width: 992px)").matches,
      headerOverlay: false,
      searchOpen: false,
      menuOpen: false,
      searchField: '',
      searchResults: {},
    };
  },
  watch: {
    searchField: function (niu, old) {
      this.requestSearch(niu);
    },
    headerOverlay: function (niu, old) {
      if (niu) $('body').addClass('overflow overlay-enabled');
      else $('body').removeClass('overflow overlay-enabled');
    },
    menuOpen: function(niu, old) {
      if (niu) $('body').addClass('overflow');
      else $('body').removeClass('overflow');
    },
  },
  created: function () {
    window.addEventListener('resize', this.onPageResize);
    var self = this;
    $(document).keyup(function (e) {
      if (e.keyCode === 'Escape' || e.key === "Escape") { // escape key maps to keycode `27`
        if (self.searchOpen) {
          self.closeSearch();
        }
      }
    });

  },
  methods: {
    onPageResize: function () {
      this.isMobile = window.matchMedia && !window.matchMedia("(min-width: 992px)").matches;
      this.menuOpen = false;
    },
    handleOpenAcessoRapido: function () {

    },
    handleSearchFocus: function () {
      this.searchOpen = true;
      // this.searchField = '';
      this.$refs.largeSearchField.focus();
      this.headerOverlay = true;
    },
    closeSearch: function () {
      this.searchOpen = false;
      this.searchField = '';
      this.searchResults = {};
      this.headerOverlay = false;
      document.body.focus();
    },
    requestSearch: function () {
      this.searchResults = {
        searching: true,
      };
      var self = this;
      $.get(this.$refs.searchForm.getAttribute('action') + '?s=' + this.searchField.trim(), function (data, status) {
        setTimeout(function () {
          if (data && data.options) {
            self.searchResults = {
              searching: false,
              options: data.options,
            };
          } else {
            self.searchResults = {
              searching: false,
              options: [],
            };
          }
        }, 1200);
      });
    },

    openMobileMenu: function() {
      this.menuOpen = !this.menuOpen;
    },

  }
});
