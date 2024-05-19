@extends('layouts.master')
@section('main-content')
@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/styles/vendor/datatables.min.css') }}">
@endsection

<div class="breadcrumb">
    <h1>{{ __('translate.Edit_Jops') }}</h1>
    <ul>
        <li><a href="/employees">{{ __('translate.Jops') }}</a></li>
        <li>{{ __('translate.Edit_Jops') }}</li>
    </ul>
</div>

<div class="separator-breadcrumb border-top"></div>

<!-- begin::main-row -->
<div class="row" id="section_Edit_employee">
    <div class="col-lg-8 mb-3">
        <div class="card">

            <!--begin::form-->
            <form @submit.prevent="Update_Employee()">
                <div class="card-body">
                    <div class="form-row ">
                        <div class="form-group col-md-6">
                            <label class="ul-form__label">{{ __('translate.jop') }} <span
                                    class="field_required">*</span></label>
                            <v-select @input="Selected_Jop" placeholder="{{ __('translate.Choose_jop') }}"
                                v-model="candidate.jop_id" :reduce="label => label.value"
                                :options="jops.map(jops => ({label: jops.job_title, value: jops.id}))">
                            </v-select>

                            <span class="error" v-if="errors && errors.jop_id">
                                @{{ errors.jop_id[0] }}
                            </span>
                        </div>
                    </div>
                    <div class="form-row ">
                        <div class="col-md-6">
                            <label for="Avatar" class="ul-form__label">{{ __('translate.Avatar') }}</label>
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
    el: '#section_Edit_employee',
    components: {
        vuejsDatepicker
    },
    data: {
        SubmitProcessing:false,
        errors:[],
        jops:@json($jops),
        candidate:@json($candidate),

    },


    methods: {

        Selected_Jop(value) {
                    if (value === null) {
                        this.candidate.jop_id = "";
                    }
                },
                changeAvatar(e){
                    let file = e.target.files[0];
                this.candidates.avatar = file;
            },

        //------------------------ Update Employee ---------------------------\\
        Update_Employee() {
            var self = this;
            self.SubmitProcessing = true;
            axios.put("/candidates/" + self.candidate.id, {
             jop_id: self.candidate.jop_id,
             cv: self.candidate.cv,
            }).then(response => {
                    self.SubmitProcessing = false;
                    window.location.href = '/candidates';
                    toastr.success('{{ __('translate.Updated_in_successfully') }}');
                    self.errors = {};
            })
            .catch(error => {
                self.SubmitProcessing = false;
                if (error.response.status == 422) {
                    self.errors = error.response.data.errors;
                }
                toastr.error('{{ __('translate.There_was_something_wronge') }}');
            });
        },

    },
    //-----------------------------Autoload function-------------------
    created () {

    },

})

</script>

@endsection
