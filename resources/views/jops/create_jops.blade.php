@extends('layouts.master')
@section('main-content')
    <div class="breadcrumb">
        <h1>{{ __('translate.Create_jops') }}</h1>
        <ul>
            <li><a href="/jops">{{ __('translate.jops') }}</a></li>
            <li>{{ __('translate.Create_jops') }}</li>
        </ul>
    </div>

    <div class="separator-breadcrumb border-top"></div>

    <!-- begin::main-row -->
    <div class="row" id="section_create_jop">
        <div class="col-lg-12 mb-3">
            <div class="card">

                <!--begin::form-->
                <form  @submit.prevent="Create_jop()">
                    <div class="card-body">
                        <div class="form-row ">
                            <div class="form-group col-md-6">
                                <label for="job_title" class="ul-form__label">{{ __('translate.job_title') }} <span
                                        class="field_required">*</span></label>
                                <input type="text" class="form-control" id="job_title"
                                    placeholder="{{ __('translate.Enter_job_title') }}" v-model="jop.job_title">
                                <span class="error" v-if="errors && errors.job_title">
                                    @{{ errors.job_title[0] }}
                                </span>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="required_candidates"
                                    class="ul-form__label">{{ __('translate.required_candidates') }} <span
                                        class="field_required">*</span></label>
                                <input type="number" class="form-control" id="required_candidates"
                                    placeholder="{{ __('translate.Enter_required_candidates') }}"
                                    v-model="jop.required_candidates">
                                <span class="error" v-if="errors && errors.required_candidates">
                                    @{{ errors.required_candidates[0] }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <label class="ul-form__label">{{ __('translate.work_area') }} <span
                                        class="field_required">*</span></label>
                                <v-select placeholder="{{ __('translate.Choose_work_area') }}" v-model="jop.work_area"
                                    :reduce="(option) => option.value"
                                    :options="[
                                        { label: 'الوسطى', value: 'Central' },
                                        { label: 'الشمالية', value: 'Northern' },
                                        { label: 'الجنوبية', value: 'Southern' },
                                        { label: 'الشرقية', value: 'Eastern' },
                                        { label: 'الغربية', value: 'Western' },

                                    ]">
                                </v-select>

                                <span class="error" v-if="errors && errors.work_area">
                                    @{{ errors.work_area[0] }}
                                </span>

                            </div>
                            <div class="form-group col-md-6">
                                <label for="city" class="ul-form__label">{{ __('translate.city') }} <span
                                        class="field_required">*</span></label>
                                <input type="text" class="form-control" id="city"
                                    placeholder="{{ __('translate.Enter_city') }}" v-model="jop.city">
                                <span class="error" v-if="errors && errors.city">
                                    @{{ errors.city[0] }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <label class="ul-form__label">{{ __('translate.academic_qualification') }} <span
                                        class="field_required">*</span></label>
                                <v-select placeholder="{{ __('translate.Choose_academic_qualification') }}"
                                    v-model="jop.academic_qualification" :reduce="(option) => option.value"
                                    :options="[
                                        { label: 'ابتداثي', value: 'Primary' },
                                        { label: 'متوسط', value: 'Intermediate' },
                                        { label: 'دبلوم', value: 'Diploma' },
                                        { label: 'بكالوريويس', value: 'Bachelor' },
                                        { label: 'ماجستر', value: 'Master' },
                                        { label: 'دكتوراه', value: 'Ph.D' },
                                        { label: 'طالب', value: 'Student' }
                                    ]">
                                </v-select>

                                <span class="error" v-if="errors && errors.academic_qualification">
                                    @{{ errors.academic_qualification[0] }}
                                </span>
                                <input type="text" class="form-control" id="specialization"
                                    placeholder="{{ __('translate.Enter_specialization') }}"
                                    v-model="jop.specialization">
                            </div>

                            <div class="col-md-6">
                                <label class="ul-form__label">{{ __('translate.language_level') }} <span
                                        class="field_required">*</span></label>
                                <v-select placeholder="{{ __('translate.Choose_language_level') }}"
                                    v-model="jop.language_level" :reduce="(option) => option.value"
                                    :options="[
                                        { label: 'مبتدى', value: 'Beginner' },
                                        { label: 'متوسط', value: 'Intermediate' },
                                        { label: 'متقدم', value: 'Advanced' }
                                    ]">
                                </v-select>

                                <span class="error" v-if="errors && errors.academic_qualification">
                                    @{{ errors.language_level[0] }}
                                </span>
                            </div>
                            <div class="form-group col-md-6">
                                <label  class="ul-form__label">{{ __('translate.nationality') }} <span
                                        class="field_required">*</span></label>
                                <input type="text" class="form-control" id="nationality"
                                placeholder="{{ __('translate.Choose_nationality') }}" v-model="jop.nationality">
                                    <span class="error" v-if="errors && errors.nationality">
                                        @{{ errors.nationality[0] }}
                                    </span>
                            </div>

                            <div class="col-md-6">
                                <label class="ul-form__label">{{ __('translate.disabilities_allowed') }} <span
                                        class="field_required">*</span></label>
                                <v-select placeholder="{{ __('translate.Choose_disabilities_allowed') }}"
                                    v-model="jop.disabilities_allowed" :reduce="(option) => option.value"
                                    :options="[
                                        { label: 'نعم', value: 'Yes' },
                                        { label: 'لا', value: 'No' },

                                    ]">
                                </v-select>

                                <span class="error" v-if="errors && errors.disabilities_allowed">
                                    @{{ errors.disabilities_allowed[0] }}
                                </span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="disability_type" class="ul-form__label">{{ __('translate.disability_type') }}
                                    <span class="field_required">*</span></label>
                                <input type="string" class="form-control" id="disability_type"
                                    placeholder="{{ __('translate.Enter_disability_type') }}"
                                    v-model="jop.disability_type">
                                <span class="error" v-if="errors && errors.disability_type">
                                    @{{ errors.disability_type[0] }}
                                </span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="required_age" class="ul-form__label">{{ __('translate.required_age') }} <span
                                        class="field_required">*</span></label>
                                <input type="number" class="form-control" id="required_age"
                                    placeholder="{{ __('translate.Enter_required_age') }}"
                                    v-model="jop.required_age">
                                <span class="error" v-if="errors && errors.required_age">
                                    @{{ errors.required_age[0] }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <label class="ul-form__label">{{ __('translate.work_type') }} <span
                                        class="field_required">*</span></label>
                                <v-select placeholder="{{ __('translate.Choose_work_type') }}"
                                    v-model="jop.work_type" :reduce="(option) => option.value"
                                    :options="[
                                        { label: 'عن بعد', value: 'remotly' },
                                        { label: 'حضوري', value: 'onSite' },
                                        { label: 'كلاهما', value: 'hybrid' },

                                    ]">
                                </v-select>

                                <span class="error" v-if="errors && errors.work_type">
                                    @{{ errors.work_type[0] }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <label class="ul-form__label">{{ __('translate.Gender') }} <span
                                        class="field_required">*</span></label>
                                <v-select @input="Selected_Gender" placeholder="{{ __('translate.Choose_Gender') }}"
                                    v-model="jop.gender" :reduce="(option) => option.value"
                                    :options="[
                                        { label: 'ذكر', value: 'male' },
                                        { label: 'أنثى', value: 'female' },
                                    ]">
                                </v-select>

                                <span class="error" v-if="errors && errors.gender">
                                    @{{ errors.gender[0] }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <label class="ul-form__label">{{ __('translate.working_hours') }} <span
                                        class="field_required">*</span></label>
                                <v-select placeholder="{{ __('translate.Choose_working_hours') }}"
                                    v-model="jop.working_hours" :reduce="(option) => option.value"
                                    :options="[
                                        { label: 'كامل', value: 'Full' },
                                        { label: 'جزئي', value: 'Part' },
                                    ]">
                                </v-select>

                                <span class="error" v-if="errors && errors.working_hours">
                                    @{{ errors.working_hours[0] }}
                                </span>
                            </div>


                            <div class="form-group col-md-6">
                                <label for="basic_salary" class="ul-form__label">{{ __('translate.basic_salary') }} <span
                                        class="field_required">*</span></label>
                                <input type="number" class="form-control" id="inputtext4"
                                    placeholder="{{ __('translate.basic_salary') }}" v-model="jop.basic_salary">
                                <span class="error" v-if="errors && errors.basic_salary">
                                    @{{ errors.basic_salary[0] }}
                                </span>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="housing_allowance"
                                    class="ul-form__label">{{ __('translate.housing_allowance') }} <span
                                        class="field_required">*</span></label>
                                <input type="string" class="form-control" id="inputtext4"
                                    placeholder="{{ __('translate.housing_allowance') }}"
                                    v-model="jop.housing_allowance">
                                <span class="error" v-if="errors && errors.housing_allowance">
                                    @{{ errors.housing_allowance[0] }}
                                </span>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="transportation_allowance"
                                    class="ul-form__label">{{ __('translate.transportation_allowance') }} <span
                                        class="field_required">*</span></label>
                                <input type="string" class="form-control" id="inputtext4"
                                    placeholder="{{ __('translate.transportation_allowance') }}"
                                    v-model="jop.transportation_allowance">
                                <span class="error" v-if="errors && errors.transportation_allowance">
                                    @{{ errors.transportation_allowance[0] }}
                                </span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="other_allowances"
                                    class="ul-form__label">{{ __('translate.other_allowances') }} <span
                                        class="field_required">*</span></label>
                                <input type="string" class="form-control" id="inputtext4"
                                    placeholder="{{ __('translate.other_allowances') }}"
                                    v-model="jop.other_allowances">
                                <span class="error" v-if="errors && errors.other_allowances">
                                    @{{ errors.other_allowances[0] }}
                                </span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="monthly_attendance_days"
                                    class="ul-form__label">{{ __('translate.monthly_attendance_days') }} <span
                                        class="field_required">*</span></label>
                                <input type="number" class="form-control" id="inputtext4"
                                    placeholder="{{ __('translate.monthly_attendance_days') }}"
                                    v-model="jop.monthly_attendance_days">
                                <span class="error" v-if="errors && errors.monthly_attendance_days">
                                    @{{ errors.monthly_attendance_days[0] }}
                                </span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="weekly_rest_days"
                                    class="ul-form__label">{{ __('translate.weekly_rest_days') }} <span
                                        class="field_required">*</span></label>
                                <input type="number" class="form-control" id="inputtext4"
                                    placeholder="{{ __('translate.weekly_rest_days') }}"
                                    v-model="jop.weekly_rest_days">
                                <span class="error" v-if="errors && errors.weekly_rest_days">
                                    @{{ errors.weekly_rest_days[0] }}
                                </span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="job_description" class="ul-form__label">{{ __('translate.job_description') }}
                                    <span class="field_required">*</span></label>
                                <input type="text" class="form-control" id="job_description"
                                    placeholder="{{ __('translate.Enter_job_description') }}"
                                    v-model="jop.job_description">
                                <span class="error" v-if="errors && errors.job_description">
                                    @{{ errors.job_description[0] }}
                                </span>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="job_requirements"
                                    class="ul-form__label">{{ __('translate.job_requirements') }} <span
                                        class="field_required">*</span></label>
                                <input type="text" class="form-control" id="job_requirements"
                                    placeholder="{{ __('translate.Enter_job_requirements') }}"
                                    v-model="jop.job_requirements">
                                <span class="error" v-if="errors && errors.job_requirements">
                                    @{{ errors.job_requirements[0] }}
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
    <script src="{{ asset('assets/js/vendor/vuejs-datepicker/vuejs-datepicker.min.js') }}"></script>

    <script>
        Vue.component('v-select', VueSelect.VueSelect)
        var app = new Vue({
            el: '#section_create_jop',
            components: {
                vuejsDatepicker
            },
            data: {
                SubmitProcessing: false,
                errors: [],

                jop: {
                    job_title: "",
                    required_candidates: "",
                    work_area: "",
                    city: "",
                    academic_qualification: "",
                    specialization: "",
                    language_level: "",
                    academic_qualification: "",
                    nationality: "",
                    disabilities_allowed: "",
                    disability_type: "",
                    required_age: "",
                    work_type:"",
                    basic_salary:"",
                    gender: "",
                    working_hours: "",
                    housing_allowance: "",
                    transportation_allowance: "",
                    other_allowances: "",
                    transportation_allowance: "",
                    monthly_attendance_days:"",
                    weekly_rest_days:"",
                    job_description:"",
                    job_requirements:"",

                },
            },

                methods: {

                Selected_Gender(value) {
                    if (value === null) {
                        this.jop.gender = "";
                    }
                },

                //------------------------ Create jop ---------------------------\\
                Create_jop() {
                    var self = this;
                    self.SubmitProcessing = true;
                    axios.post("/jops", {
                            job_title: self.jop.job_title,
                            required_candidates: self.jop.required_candidates,
                            work_area: self.jop.work_area,
                            city: self.jop.city,
                            academic_qualification: self.jop.academic_qualification,
                            specialization: self.jop.specialization,
                            language_level: self.jop.language_level,
                            nationality: self.jop.nationality,

                            disabilities_allowed: self.jop.disabilities_allowed,
                            disability_type: self.jop.disability_type,
                            required_age: self.jop.required_age,
                            gender: self.jop.gender,
                            work_type: self.jop.work_type,
                            basic_salary: self.jop.basic_salary,
                            working_hours: self.jop.working_hours,
                            housing_allowance: self.jop.housing_allowance,
                            transportation_allowance: self.jop.transportation_allowance,
                            monthly_attendance_days: self.jop.monthly_attendance_days,
                            weekly_rest_days: self.jop.weekly_rest_days,
                            job_description: self.jop.job_description,
                            job_requirements: self.jop.job_requirements,


                        }).then(response => {
                            self.SubmitProcessing = false;
                            window.location.href = '/jops';
                            toastr.success('{{ __('translate.Created_in_successfully') }}');
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
            created() {

            },

        })
    </script>
@endsection
