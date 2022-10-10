require('./bootstrap')
import { createApp } from 'vue'
// import $ from 'jquery'
import { configure, defineRule } from 'vee-validate'
import Nl2br from 'vue3-nl2br'

configure({
    validateOnBlur: false,
    validateOnChange: false,
    validateOnInput: true,
    validateOnModelUpdate: false
})
const app = createApp({})
// app.config.errorHandler = () => null
// app.config.warnHandler = () => null

import Dialog from './components/common/popupSuccess.vue'
import RegisterCard from './components/common/registerCard.vue'

import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'
app.use(VueSweetalert2)

import Register from './components/user/register/index.vue'
import EventForm from './components/user/event/form.vue'
import EventConfirm from './components/user/event/confirm.vue'
import MyEvent from './components/myEvent/index.vue'
import MyEventShow from './components/myEvent/show.vue'
import MyPage from './components/myPage/index.vue'
import Profile from './components/profile/index.vue'
import EventReport from './components/eventReport/index.vue'
import ChangeName from './components/user/changeName/index.vue'
import Notification from './components/user/profile/setting-notification.vue'
import EventList from './components/user/event/index.vue'
import PopupAlert from './components/common/popupAlert.vue'
import MyPageEarn from './components/myPage/earn.vue'

app.component('nl2br', Nl2br)
app.component('popup-alert', PopupAlert)
app.component('register', Register)
app.component('event-form', EventForm)
app.component('event-confirm', EventConfirm)
app.component('my-event', MyEvent)
app.component('my-page', MyPage)
app.component('event-report', EventReport)
app.component('my-event-show', MyEventShow)
app.component('popup-success', Dialog)
app.component('register-card', RegisterCard)
app.component('profile', Profile)
app.component('change-name', ChangeName)
app.component('setting-notification', Notification)
app.component('event-list', EventList)
app.component('my-page-earn', MyPageEarn)

app.mount('#app')
