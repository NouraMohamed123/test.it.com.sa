@extends('layouts.master')
@section('main-content')
@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/styles/vendor/datatables.min.css') }}">
@endsection

<div class="breadcrumb">
    <h1>{{ __('translate.Create_candidates') }}</h1>
    <ul>
        <li><a href="/candidates">{{ __('translate.candidates') }}</a></li>
        <li>{{ __('translate.Create_candidates') }}</li>
    </ul>
</div>

<div class="separator-breadcrumb border-top"></div>

<!-- begin::main-row -->
<div class="row" id="section_create_employee">
    <div class="col-lg-8 mb-3">
        <div class="card">

            <!--begin::form-->
            <form @submit.prevent="Create_Employee()" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="form-row ">
                        <div class="col-md-6">
                            <label class="ul-form__label">{{ __('translate.jop') }} <span
                                    class="field_required">*</span></label>
                            <v-select @input="Selected_Jop" placeholder="{{ __('translate.Choose_jop') }}"
                                v-model="candidates.jop_id" :reduce="label => label.value"
                                :options="jops.map(jops => ({ label: jops.job_title, value: jops.id }))">
                            </v-select>

                            <span class="error" v-if="errors && errors.status">
                                @{{ errors.status[0] }}
                            </span>
                        </div>
                    </div>
                    <div class="form-row ">
                        <div class="col-md-6">
                            <label for="Avatar" class="ul-form__label">{{ __('translate.Cv') }}</label>
                            <input name="Avatar" @change="changeAvatar" type="file" class="form-control"
                                id="Avatar">
                            <span class="error" v-if="errors && errors.avatar">
                                @{{ errors.avatar[0] }}
                            </span>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-primary" :disabled="SubmitProcessing">
                                {{ __('translate.Submit') }}
                            </button>
                            <div v-once class="typo__p" v-if="SubmitProcessing">
                                <div class="spinner spinner-primary mt-3"></div>
                            </div>
                        </div>
                    </div>
            </form>

            <!-- end::form -->
        </div>
    </div>

</div>
@endsection

@section('page-js')
<script src="{{ asset('assets/js/vendor/datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables.script.js') }}"></script>
<script src="{{ asset('assets/js/vendor/vuejs-datepicker/vuejs-datepicker.min.js') }}"></script>
<script>
    Vue.component('v-select', VueSelect.VueSelect)
    var app = new Vue({
        el: '#section_create_employee',
        components: {
            vuejsDatepicker
        },
        data: {
            SubmitProcessing: false,
            errors: [],
            jops: @json($jops),

            candidates: {
                jop_id: "",
                avatar: ""

            },
        },

        methods: {
            Selected_Jop(value) {
                if (value === null) {
                    this.candidates.jop_id = "";
                }


            },
            changeAvatar(e) {
                let file = e.target.files[0];
                this.candidates.avatar = file;

            },

            //------------------------ Create Employee ---------------------------\\
            Create_Employee() {
                var self = this;
                self.SubmitProcessing = true;


                if (!self.data || !(self.data instanceof FormData)) {
                    self.data = new FormData();
                }

                self.data.append("jop_id", self.candidates.jop_id);
                self.data.append("avatar", self.candidates.avatar);

                axios.post("/candidates", self.data)
                    .then(response => {
                        self.SubmitProcessing = false;
                        window.location.href = '/candidates';
                        toastr.success('{{ __('translate.Created_in_successfully') }}');
                        self.errors = {};
                    })
                    .catch(error => {
                        self.SubmitProcessing = false;
                        if (error.response && error.response.status == 422) {
                            self.errors = error.response.data.errors;
                        }
                        toastr.error('{{ __('translate.There_was_something_wronge') }}');
                    });
            }

            // Create_Employee() {
            //     var self = this;
            //     self.SubmitProcessing = true;
            //     console.log(self.candidates.avatar );
            //     axios.post("/candidates",{
            //         jop_id:self.candidates.jop_id,
            //         Avatar:self.candidates.avatar

            //     }).then(response => {
            //             self.SubmitProcessing = false;
            //             window.location.href = '/candidates';
            //             toastr.success('{{ __('translate.Created_in_successfully') }}');
            //             self.errors = {};
            //         })
            //         .catch(error => {
            //             self.SubmitProcessing = false;
            //             if (error.response.status == 422) {
            //                 self.errors = error.response.data.errors;
            //             }
            //             toastr.error('{{ __('translate.There_was_something_wronge') }}');
            //         });
            // },

        },
        //-----------------------------Autoload function-------------------
        created() {

        },

    })
</script>
@endsection
