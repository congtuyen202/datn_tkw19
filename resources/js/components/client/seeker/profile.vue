<template>
  <div class="_dashboard_content_body py-3 px-3">
    <VeeForm
      as="div"
      v-slot="{ handleSubmit }"
      @invalid-submit="onInvalidSubmit"
    >
      <form
        class="row"
        @submit="handleSubmit($event, onSubmit)"
        ref="formData"
        method="POST"
      >
        <Field type="hidden" :value="csrfToken" name="_token" />

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="col-sm-12 text-center">
            <div class="display-div_custom" style="margin-left: 40%; border-radius: 20px">
              <div
                class="img-display_author d-flex"
                id="img-preview"
                @click="chooseImage()"
                role="button"
              >
                <img
                  v-if="Media === '' && checkImage == '' && !filePreview"
                  src="https://i.pinimg.com/236x/15/46/2e/15462ed447e25356837b32a7e22e538f.jpg"
                  alt=""
                />
                <div style="display: none">
                  <input
                    type="file"
                    @change="onChange"
                    ref="fileInput"
                    accept="image/*"
                    name="images"
                  />
                </div>
                <img
                  v-if="!filePreview && Media != ''"
                  :src="Media"
                  class="img"
                />

                <div
                  class="img-display_author d-flex"
                  id="img-preview"
                  @click="chooseImage()"
                  role="button"
                >
                  <div style="display: none">
                    <input
                      type="file"
                      id="file"
                      @change="onChange"
                      ref="fileInput"
                      accept="image/*"
                      name="images"
                    />
                  </div>
                  <img v-if="filePreview" :src="filePreview" class="img" />
                </div>
              </div>
              <input type="hidden" name="images" :value="Media" />
              <div class="text-center">
                <span class="error">{{ errmsgCheckImage }}</span>
              </div>
            </div>
          </div>
        </div>
        

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <br>
          <br>
          <div class="row">
            <div class="col-xl-6 col-lg-6">
              <div class="form-group">
                <label class="text-dark ft-medium">Name</label>
                <Field
                  type="text"
                  class="form-control rounded"
                  name="name"
                  rules="required|max:255"
                  v-model="model.name"
                />
                <ErrorMessage class="error" name="name" />
              </div>
            </div>
            <div class="col-xl-6 col-lg-6">
              <div class="form-group">
                <label class="text-dark ft-medium">Email</label>
                <Field
                  type="email"
                  class="form-control"
                  v-model="model.email"
                  rules="required|email|max:255"
                  name="email"
                />
                <ErrorMessage class="error" name="email" />
              </div>
            </div>
            <div class="col-xl-6 col-lg-6">
              <div class="form-group">
                <label class="text-dark ft-medium">S??? ??i???n tho???i</label>
                <Field
                  type="text"
                  class="form-control"
                  v-model="valueSelect.phone"
                  name="phone"
                  rules="required|telephone"
                />
              </div>
              <ErrorMessage class="error" name="phone" />
            </div>
            <div class="col-xl-6 col-lg-6">
              <div class="form-group">
                <label class="text-dark ft-medium">?????a ch???</label>
                <Field
                  type="text"
                  v-model="valueSelect.address"
                  class="form-control"
                  name="address"
                  rules="required|max:255"
                />
              </div>
              <ErrorMessage class="error" name="address" />
            </div>
            <div class="col-xl-6 col-lg-6">
              <div class="form-group">
                <label class="text-dark ft-medium">Kinh Nghi???m</label>
                <Field
                  name="experience_id"
                  as="select"
                  v-model="valueSelect.experience_id"
                  rules="required"
                  class="form-control"
                >
                  <option value disabled selected>Ch???n Kinh Nghi???m</option>
                  <option
                    v-for="item in data.experience"
                    :key="item.id"
                    :value="item.id"
                  >
                    {{ item.label }}
                  </option>
                </Field>
                <ErrorMessage class="error" name="experience_id" />
              </div>
            </div>
            <div class="col-xl-6 col-lg-6">
              <div class="form-group">
                <label class="text-dark ft-medium">Tr??nh ?????</label>
                <Field
                  name="lever_id"
                  as="select"
                  v-model="valueSelect.lever_id"
                  rules="required"
                  class="form-control"
                >
                  <option value disabled selected>Ch???n Tr??nh ?????</option>
                  <option
                    v-for="item in data.lever"
                    :key="item.id"
                    :value="item.id"
                  >
                    {{ item.label }}
                  </option>
                </Field>
                <ErrorMessage class="error" name="lever_id" />
              </div>
            </div>
            <div class="col-xl-6 col-lg-6">
              <div class="form-group">
                <label class="text-dark ft-medium">M???c l????ng Mong Mu???n</label>
                <Field
                  name="wage_id"
                  as="select"
                  v-model="valueSelect.wage_id"
                  rules="required"
                  class="form-control"
                >
                  <option value disabled selected>Ch???n M???c L????ng</option>
                  <option
                    v-for="item in data.wage"
                    :key="item.id"
                    :value="item.id"
                  >
                    {{ item.label }}
                  </option>
                </Field>
                <ErrorMessage class="error" name="wage_id" />
              </div>
            </div>
            <div class="col-xl-6 col-lg-6">
              <div class="form-group">
                <label class="text-dark ft-medium">Ng??nh Ngh???</label>
                <Field
                  name="profession_id"
                  as="select"
                  v-model="valueSelect.profession_id"
                  rules="required"
                  class="form-control"
                >
                  <option value disabled selected>Ch???n Ng??nh Ngh???</option>
                  <option
                    v-for="item in data.profession"
                    :key="item.id"
                    :value="item.id"
                  >
                    {{ item.label }}
                  </option>
                </Field>
                <ErrorMessage class="error" name="profession_id" />
              </div>
            </div>
            <div class="col-xl-6 col-lg-6">
              <div class="form-group">
                <label class="text-dark ft-medium">Th???i Gian L??m Vi???c</label>
                <Field
                  name="time_work_id"
                  as="select"
                  v-model="valueSelect.time_work_id"
                  rules="required"
                  class="form-control"
                >
                  <option value disabled selected>Ch???n Th???i Gian</option>
                  <option
                    v-for="item in data.timework"
                    :key="item.id"
                    :value="item.id"
                  >
                    {{ item.label }}
                  </option>
                </Field>
                <ErrorMessage class="error" name="time_work_id" />
              </div>
            </div>
            <div class="col-xl-6 col-lg-6">
              <div class="form-group">
                <label class="text-dark ft-medium">K??? n??ng</label>
                <Field
                  class="form-control"
                  v-model="value"
                  name="skill_id"
                  rules="required"
                >
                  <Multiselect
                    placeholder="Ch???n K??? n??ng"
                    v-model="value"
                    mode="tags"
                    :searchable="true"
                    :options="options"
                    label="label"
                    track-by="label"
                    :infinite="true"
                    :object="true"
                  />
                </Field>
                <ErrorMessage class="error" name="skill_id" />
              </div>
            </div>

            <div class="col-xl-12 col-lg-12">
              <div class="form-group">
                <button
                  type="submit"
                  class="btn btn-md ft-medium text-light rounded theme-bg btn-register-employer"
                >
                  C???p nh???t
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </VeeForm>
  </div>
</template>
<script>
import {
  Form as VeeForm,
  Field,
  ErrorMessage,
  defineRule,
  configure
} from 'vee-validate'
import Multiselect from '@vueform/multiselect'
import { localize } from '@vee-validate/i18n'
import * as rules from '@vee-validate/rules'
import $ from 'jquery'
import axios from 'axios'
import { Notyf } from 'notyf'
import 'notyf/notyf.min.css'
export default {
  setup() {
    Object.keys(rules).forEach((rule) => {
      if (rule != 'default') {
        defineRule(rule, rules[rule])
      }
    })
  },
  components: {
    VeeForm,
    Field,
    ErrorMessage,
    Multiselect
  },
  props: ['data'],
  data: function () {
    return {
      csrfToken: Laravel.csrfToken,
      model: this.data.user ?? '',
      filePreview: '',
      loading: false,
      value: [],
      options: [],
      valueSelect: this.data.user.get_profile_use ?? {},
      checkImage: '',
      errmsgCheckImage: '',
      Media: '',
      deleteImage: ''
    }
  },

  created() {
    this.Media = this.data.user.get_profile_use
      ? this.data.user.get_profile_use.images
      : 1
    if (this.data.getskill.getskill != null) {
      this.data.getskill.getskill.map((e) => {
        this.value.push({
          value: e.id,
          label: e.name
        })
      })
    }

    this.data.skill.map((e) => {
      this.options.push({
        value: e.id,
        label: e.label
      })
    })
    let messError = {
      en: {
        fields: {
          images: {
            required: '???nh kh??ng ???????c ????? tr???ng'
          },
          name: {
            required: 'T??n kh??ng ???????c ????? tr???ng',
            max: 'T??n kh??ng ???????c v?????t qu?? 255 k?? t???'
          },
          email: {
            required: 'Email kh??ng ???????c ????? tr???ng',
            max: 'Email kh??ng ???????c v?????t qu?? 255 k?? t???',
            email: 'Email kh??ng ????ng ?????nh d???ng'
          },
          phone: {
            required: 'S??? ??i???n tho???i kh??ng ???????c ????? tr???ng',
            telephone: 'S??? ??i???n tho???i kh??ng ????ng ?????nh d???ng'
          },
          address: {
            required: '?????a ch??? kh??ng ???????c ????? tr???ng',
            max: '?????a ch??? kh??ng ???????c v?????t qu?? 255 k?? t???'
          },
          experience_id: {
            required: 'Kinh nghi???m kh??ng ???????c b??? tr???ng'
          },
          lever_id: {
            required: 'Tr??nh ????? kh??ng ???????c b??? tr???ng'
          },
          wage_id: {
            required: 'M???c l????ng kh??ng ???????c b??? tr???ng'
          },
          profession_id: {
            required: 'Ng??nh ngh??? kh??ng ???????c b??? tr???ng'
          },
          time_work_id: {
            required: 'Th???i gian kh??ng ???????c b??? tr???ng'
          },
          skill_id: {
            required: 'K??? n??ng kh??ng ???????c b??? tr???ng'
          }
        }
      }
    }
    configure({
      generateMessage: localize(messError)
    })
  },
  methods: {
    onInvalidSubmit({ values, errors, results }) {
      if (this.checkImage == 1) {
        this.errmsgCheckImage = '???nh kh??ng ???????c ????? tr???ng'
      }
      let firstInputError = Object.entries(errors)[0][0]
      this.$el.querySelector('input[name=' + firstInputError + ']').focus()
      $('html, body').animate(
        {
          scrollTop: $('input[name=' + firstInputError + ']').offset().top - 150
        },
        500
      )
    },
    onSubmit() {
      if (this.checkImage == 1) {
        this.errmsgCheckImage = '???? c?? 1 l???i s???y ra'
      } else {
        let that = this
        $('.loading-div').removeClass('hidden')
        axios
          .post(that.data.urlStore, {
            _token: Laravel.csrfToken,
            valueSelect: that.valueSelect,
            name: that.model.name,
            email: that.model.email,
            skill_id: that.value
          })
          .then(function (response) {
            const notyf = new Notyf({
              duration: 6000,
              position: {
                x: 'right',
                y: 'bottom'
              },
              types: [
                {
                  type: 'error',
                  duration: 8000,
                  dismissible: true
                }
              ]
            })
            console.log(response.data.status)
            if (response.data.status == 403) {
              setTimeout(function () {
                location.reload()
              }, 1100)
              return notyf.error(response.data.message)
            }
            if (response.data.status == 400) {
              setTimeout(function () {
                location.reload()
              }, 1100)
              return notyf.warning(response.data.message)
            }
            setTimeout(function () {
              location.reload()
            }, 1100)
            return notyf.success(response.data.message)
          })
          .catch((error) => {
            console.log(error)
          })
      }
    },
    chooseImage() {
      this.$refs['fileInput'].click()
    },
    onChange(e) {
      this.deleteImage = e.target.files[0]
      if (e.target) {
        let Image = e.target.files[0]
        if (Image.type.includes('image/')) {
          this.errmsgCheckImage = ''
          this.checkImage = 2
        } else {
          this.errmsgCheckImage = '???nh ph???i ????ng ?????nh d???ng'
          return
        }
        if (Image.size >= 5242880) {
          this.errmsgCheckImage = '???nh kh??ng ???????c qu?? 5mb'
          this.checkImage = 1
        } else {
          this.errmsgCheckImage = ''
          this.checkImage = 2
        }
        this.model.images = e.target.files[0]
        let fileInput = this.$refs.fileInput
        let imgFile = fileInput.files

        if (imgFile && imgFile[0]) {
          let reader = new FileReader()
          reader.onload = (e) => {
            this.filePreview = e.target.result
          }
          reader.readAsDataURL(imgFile[0])
        }
      }
    }
  }
}
</script>
<style>
.error {
  color: rgb(255, 80, 80);
  margin-left: 5px;
  margin-top: 5px;
}
.display-div_custom {
  border: solid 1px;
  border-radius: 4px;
  height: 170px;
  width: 200px;
}
.img-display_author {
  height: 168px;
  max-width: 200px;
}
.img {
  max-width: 135px;
  margin-left: 30px;
  margin-top: 15px;
  margin-bottom: 15px;
}
</style>
<style src="@vueform/multiselect/themes/default.css"></style>