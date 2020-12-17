
require('./bootstrap');

window.Vue = require('vue');
import moment from 'moment';
import VueProgressBar from 'vue-progressbar';
import { Form, HasError, AlertError } from 'vform';


window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

//Album components
Vue.component('albumcreate', require('./components/AlbumCreateComponent.vue').default);
Vue.component('albumlist', require('./components/AlbumIndexComponent.vue').default);
Vue.component('albumedit', require('./components/AlbumEditComponent.vue').default);
//category components
Vue.component('catlist', require('./components/categoryIndexComponent.vue').default);
Vue.component('catcreate', require('./components/categoryCreateComponent.vue').default);
Vue.component('catedit', require('./components/categoryEditComponent.vue').default);

Vue.component('category', require('./components/categoryComponent.vue').default);


Vue.filter('upText', function (text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
});

Vue.filter('myDate', function (created) {
    return moment(created).format("MMM Do YYYY");
});

//progressbar
const options = {
    color: '#bffaf3',
    failedColor: '#874b4b',
    thickness: '5px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
}
Vue.use(VueProgressBar, options)
//progressbar end
//Component communication
window.Fire = new Vue();


const app = new Vue({
    el: '#app',
});
