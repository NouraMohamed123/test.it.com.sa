@extends('layouts.master')
@section('main-content')


<div class="breadcrumb">
    <h1>{{ __('translate.Edit_Employee') }}</h1>
    <ul>
        <li><a href="/employees">{{ __('translate.Employees') }}</a></li>
        <li>{{ __('translate.Edit_Employee') }}</li>
    </ul>
</div>

<div class="separator-breadcrumb border-top"></div>

<!-- begin::main-row -->
<div class="row" id="section_Edit_employee">
    <div class="col-lg-12 mb-3">
        <div class="card">

            <!--begin::form-->
            <form @submit.prevent="Update_Employee()">
                <div class="card-body">
                    <div class="form-row">
                    
                        <div class="form-group col-md-6">
                            <label for="FirstName" class="ul-form__label">{{ __('translate.FirstName') }} <span
                                    class="field_required">*</span></label>
                            <input type="text" class="form-control" id="FirstName"
                                placeholder="{{ __('translate.Enter_FirstName') }}" v-model="employee.firstname">
                            <span class="error" v-if="errors && errors.firstname">
                                @{{ errors.firstname[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="lastname" class="ul-form__label">{{ __('translate.Second_name') }} <span
                                    class="field_required">*</span></label>
                            <input type="text" class="form-control" id="lastname"
                                placeholder="{{ __('translate.Enter_Second_name') }}" v-model="employee.lastname">
                            <span class="error" v-if="errors && errors.lastname">
                                @{{ errors.lastname[0] }}
                            </span>
                        </div>
                        
                        
                        <div class="form-group col-md-6">
                            <label for="third_name" class="ul-form__label">{{ __('translate.Third_name') }} <span
                                    class="field_required">*</span></label>
                            <input type="text" class="form-control" id="third_name"
                                placeholder="{{ __('translate.Enter_Third_name') }}" v-model="employee.third_name">
                            <span class="error" v-if="errors && errors.third_name">
                                @{{ errors.third_name[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="fourth_name" class="ul-form__label">{{ __('translate.LastName') }} <span
                                    class="field_required">*</span></label>
                            <input type="text" class="form-control" id="fourth_name"
                                placeholder="{{ __('translate.Enter_LastName') }}" v-model="employee.fourth_name">
                            <span class="error" v-if="errors && errors.fourth_name">
                                @{{ errors.fourth_name[0] }}
                            </span>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="Avatar" class="ul-form__label">{{ __('translate.Avatar') }}</label>
                            <input name="Avatar" @change="changeAvatar" type="file" class="form-control"
                                id="Avatar">
                            <span class="error" v-if="errors && errors.avatar">
                                @{{ errors.avatar[0] }}
                            </span>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="Scene_image" class="ul-form__label">{{ __('translate.Picture_scene') }}</label>
                            <input name="Scene_image" @change="changeSceneImage" type="file" class="form-control"
                                id="Scene_image">
                            <span class="error" v-if="errors && errors.scene_image">
                                @{{ errors.scene_image[0] }}
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label class="ul-form__label">{{ __('translate.Gender') }} <span
                                    class="field_required">*</span></label>
                            <v-select @input="Selected_Gender" placeholder="{{ __('translate.Choose_Gender') }}"
                                v-model="employee.gender" :reduce="(option) => option.value" :options="
                                    [
                                        {label: 'ذكر', value: 'male'},
                                        {label: 'أنثى', value: 'female'},
                                    ]">
                            </v-select>

                            <span class="error" v-if="errors && errors.gender">
                                @{{ errors.gender[0] }}
                            </span>
                        </div>
                        
                        <!-- marital_status options -->
                        <div class="col-md-6">
                            <label class="ul-form__label">{{ __('translate.Marital_status') }} <span
                                    class="field_required">*</span></label>
                            <v-select @input="Selected_Gender" placeholder="{{ __('translate.Enter_Marital_status') }}"
                                v-model="employee.marital_status" :reduce="(option) => option.value" :options="
                                    [
                                        {label: ' أعزب/ة', value: 'single'},
                                        {label: ' متزوج/ة', value: 'married'},
                                        {label: 'مطلق/ة', value: 'divorced'},
                                        {label: 'أرمل/ة', value: 'widowed'},
                                    ]">
                            </v-select>

                            <span class="error" v-if="errors && errors.marital_status">
                                @{{ errors.marital_status[0] }}
                            </span>
                        </div>

                        <div class="col-md-6 form-group">
                            <label class="ul-form__label" for="picker3">{{ __('translate.Birth_date') }}</label>

                            <vuejs-datepicker id="birth_date" name="birth_date"
                                placeholder="{{ __('translate.Enter_Birth_date') }}" v-model="employee.birth_date"
                                input-class="form-control" format="yyyy-MM-dd"
                                @closed="employee.birth_date=formatDate(employee.birth_date)">
                            </vuejs-datepicker>

                        </div>
                        
                        <div class="col-md-6 form-group">
                            <label class="ul-form__label" for="date_of_birth_hijri">{{ __('translate.Date_birth_Hijri_Gregorian') }}<span
                                    class="field_required">*</span></label>

                            <vuejs-datepicker id="date_of_birth_hijri" name="date_of_birth_hijri"
                                placeholder="{{ __('translate.Enter_Date_birth_Hijri_Gregorian') }}" v-model="employee.date_of_birth_hijri"
                                input-class="form-control" format="yyyy-MM-dd"
                                @closed="employee.date_of_birth_hijri=formatDate(employee.date_of_birth_hijri)">
                            </vuejs-datepicker>

                        </div>

                        <div class="form-group col-md-6">
                            <label for="inputEmail4" class="ul-form__label">{{ __('translate.Email_Address') }} <span
                                    class="field_required">*</span></label>
                            <input type="email" class="form-control" id="inputtext4"
                                placeholder="{{ __('translate.Enter_email_address') }}" v-model="employee.email">
                            <span class="error" v-if="errors && errors.email">
                                @{{ errors.email[0] }}
                            </span>
                        </div>
                        @if (auth()->user()->role_users_id == 1)
                            <div class="form-group col-md-6">
                                <label class="ul-form__label">{{ __('translate.Role') }} <span
                                        class="field_required">*</span></label>
                                <v-select @input="Selected_Role" placeholder="{{ __('translate.Choose_Role') }}"
                                    v-model="employee.role_users_id" :reduce="label => label.value"
                                    :options="roles.map(roles => ({label: roles.name, value: roles.id}))">
                                </v-select>

                                <span class="error" v-if="errors && errors.role_users_id">
                                    @{{ errors.role_users_id[0] }}
                                </span>
                            </div>
                        @endif

                        <div class="col-md-6">
                            <label for="password" class="ul-form__label">{{ __('translate.Password') }} <span
                                    class="field_required">*</span></label>
                            <input type="password" v-model="employee.password" class="form-control" id="password"
                                placeholder="{{ __('translate.min_6_characters') }}">
                            <span class="error" v-if="errors && errors.password">
                                @{{ errors.password[0] }}
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation"
                                class="ul-form__label">{{ __('translate.Repeat_Password') }} <span
                                    class="field_required">*</span></label>
                            <input type="password" v-model="employee.password_confirmation" class="form-control"
                                id="password_confirmation" placeholder="{{ __('translate.Repeat_Password') }}">
                            <span class="error" v-if="errors && errors.password_confirmation">
                                @{{ errors.password_confirmation[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="country" class="ul-form__label">{{ __('translate.Country') }} <span
                                    class="field_required">*</span></label>
                            <input type="text" class="form-control" id="country"
                                placeholder="{{ __('translate.Enter_Country') }}" v-model="employee.country">
                            <span class="error" v-if="errors && errors.country">
                                @{{ errors.country[0] }}
                            </span>
                        </div>
                        
                        
                        <div class="form-group col-md-6">
                            <label for="City" class="ul-form__label">{{ __('translate.City') }} <span
                                    class="field_required">*</span></label>
                            <input type="text" class="form-control" id="City"
                                placeholder="{{ __('translate.Enter_City') }}" v-model="employee.city">
                            <span class="error" v-if="errors && errors.city">
                                @{{ errors.city[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="phone" class="ul-form__label">{{ __('translate.Phone_Number') }} <span
                                    class="field_required">*</span></label>
                            <input type="text" class="form-control" id="phone"
                                placeholder="{{ __('translate.Enter_Phone_Number') }}" v-model="employee.phone">
                            <span class="error" v-if="errors && errors.phone">
                                @{{ errors.phone[0] }}
                            </span>
                        </div>
                        
                                                <div class="col-md-6">
                            <label class="ul-form__label">{{ __('translate.National_address_data') }} <span
                                    class="field_required">*</span></label>
                            <v-select @input="Selected_Gender" placeholder="{{ __('translate.Enter_National_address_data') }}"
                                v-model="employee.address" :reduce="(option) => option.value" :options="
                                    [
                                        {label: 'الوسطى ', value: 'Central'},
                                        {label: 'الشمالية ', value: 'North'},
                                        {label: 'الجنوبية ', value: 'South'},
                                        {label: 'الشرقية ', value: 'Eastern'},
                                        {label: 'الغربية  ', value: 'Western'},
                                    ]">
                            </v-select>

                            <span class="error" v-if="errors && errors.address">
                                @{{ errors.address[0] }}
                            </span>
                        </div>
                        
                                                <!-- language_level options -->
                        <div class="col-md-6">
                            <label class="ul-form__label">{{ __('translate.Language_Level') }} <span
                                    class="field_required">*</span></label>
                            <v-select @input="Selected_Gender" placeholder="{{ __('translate.Enter_Language_Level') }}"
                                v-model="employee.language_level" :reduce="(option) => option.value" :options="
                                    [
                                        {label: 'مبتدى', value: 'beginner'},
                                        {label: 'متوسط', value: 'middle'},
                                        {label: 'متقدم', value: 'advanced'},
                                    ]">
                            </v-select>

                            <span class="error" v-if="errors && errors.language_level">
                                @{{ errors.language_level[0] }}
                            </span>
                        </div>
                        
                        
                        <!-- educational_qualification options -->
                        <div class="col-md-6">
                            <label class="ul-form__label">{{ __('translate.Qualification') }} <span
                                    class="field_required">*</span></label>
                            <v-select @input="Selected_Gender" placeholder="{{ __('translate.Enter_Qualification') }}"
                                v-model="employee.educational_qualification" :reduce="(option) => option.value" :options="
                                    [
                                        {label: 'ابتدائي  ', value: 'primary'},
                                        {label: 'متوسط  ', value: 'middle'},
                                        {label: 'طالب /ة  ', value: 'Student'},
                                        {label: 'بكالوريويس', value: 'Bachelor'},
                                        {label: 'ماجستر', value: 'Master'},
                                        {label: 'دكتوراه', value: 'doctorate'},
                                    ]">
                            </v-select>

                            <span class="error" v-if="errors && errors.educational_qualification">
                                @{{ errors.educational_qualification[0] }}
                            </span>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="specialization" class="ul-form__label">{{ __('translate.diplom_doctorate') }}</label>
                            <input type="text" class="form-control" id="specialization"
                                placeholder="{{ __('translate.Enter_diploma_bachelor_master_doctorate') }}" v-model="employee.specialization">
                            <span class="error" v-if="errors && errors.specialization">
                                @{{ errors.specialization[0] }}
                            </span>
                        </div>


                        <div class="col-md-6 form-group">
                            <label class="ul-form__label" for="picker3">{{ __('translate.Joining_Date') }}<span
                                    class="field_required">*</span></label>

                            <vuejs-datepicker id="joining_date" name="joining_date"
                                placeholder="{{ __('translate.Enter_Joining_Date') }}" v-model="employee.joining_date"
                                input-class="form-control" format="yyyy-MM-dd"
                                @closed="employee.joining_date=formatDate(employee.joining_date)">
                            </vuejs-datepicker>

                        </div>

                        <div class="form-group col-md-6">
                            <label class="ul-form__label">{{ __('translate.Company') }} <span
                                    class="field_required">*</span></label>
                            <v-select @input="Selected_Company" placeholder="{{ __('translate.Choose_Company') }}"
                                v-model="employee.company_id" :reduce="label => label.value"
                                :options="companies.map(companies => ({label: companies.name, value: companies.id}))">
                            </v-select>

                            <span class="error" v-if="errors && errors.company_id">
                                @{{ errors.company_id[0] }}
                            </span>
                        </div>


                        <div class="form-group col-md-6">
                            <label class="ul-form__label">{{ __('translate.Department') }} <span
                                    class="field_required">*</span></label>
                            <v-select @input="Selected_Department" placeholder="{{ __('translate.Choose_Department') }}"
                                v-model="employee.department_id" :reduce="label => label.value"
                                :options="departments.map(departments => ({label: departments.department, value: departments.id}))">
                            </v-select>

                            <span class="error" v-if="errors && errors.department_id">
                                @{{ errors.department_id[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-6">
                            <label class="ul-form__label">{{ __('translate.Designation') }} <span
                                    class="field_required">*</span></label>
                            <v-select @input="Selected_Designation"
                                placeholder="{{ __('translate.Choose_Designation') }}" v-model="employee.designation_id"
                                :reduce="label => label.value"
                                :options="designations.map(designations => ({label: designations.designation, value: designations.id}))">
                            </v-select>

                            <span class="error" v-if="errors && errors.designation_id">
                                @{{ errors.designation_id[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-6">
                            <label class="ul-form__label">{{ __('translate.Office_Shift') }} <span
                                    class="field_required">*</span></label>
                            <v-select @input="Selected_Office_shift"
                                placeholder="{{ __('translate.Choose_Office_Shift') }}"
                                v-model="employee.office_shift_id" :reduce="label => label.value"
                                :options="office_shifts.map(office_shifts => ({label: office_shifts.name, value: office_shifts.id}))">
                            </v-select>

                            <span class="error" v-if="errors && errors.office_shift_id">
                                @{{ errors.office_shift_id[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="Job_title" class="ul-form__label">{{ __('translate.Job_title') }}</label>
                            <input type="text" class="form-control" id="Job_title"
                                placeholder="{{ __('translate.Enter_Job_title') }}" v-model="employee.job_title">
                            <span class="error" v-if="errors && errors.job_title">
                                @{{ errors.job_title[0] }}
                            </span>
                        </div>
                        
                                                <div class="form-group col-md-6">
                            <label for="job_description" class="ul-form__label">{{ __('translate.Job_Description') }}</label>
                            <input type="text" class="form-control" id="job_description"
                                placeholder="{{ __('translate.Enter_Job_Description') }}" v-model="employee.job_description">
                            <span class="error" v-if="errors && errors.job_description">
                                @{{ errors.job_description[0] }}
                            </span>
                        </div>


                        <div class="form-group col-md-6">
                            <label for="supervisor_name" class="ul-form__label">{{ __('translate.Direct_responsible') }} <span
                                    class="field_required">*</span></label>
                            <input type="text" class="form-control" id="supervisor_name"
                                placeholder="{{ __('translate.Enter_Direct_responsible') }}" v-model="employee.supervisor_name">
                            <span class="error" v-if="errors && errors.supervisor_name">
                                @{{ errors.supervisor_name[0] }}
                            </span>
                        </div>

                        <!-- contract_type options  -->
                        <div class="col-md-6">
                            <label class="ul-form__label">{{ __('translate.Type_Contract') }} <span
                                    class="field_required">*</span></label>
                            <v-select @input="Selected_Gender" placeholder="{{ __('translate.Enter_Type_Contract') }}"
                                v-model="employee.contract_type" :reduce="(option) => option.value" :options="
                                    [
                                        {label: 'محدد المدة', value: 'Fixed_term'},
                                        {label: 'غير محدد المدة ', value: 'Indefinite_term'},
                                    ]">
                            </v-select>

                            <span class="error" v-if="errors && errors.contract_type">
                                @{{ errors.contract_type[0] }}
                            </span>
                        </div>

                         <!-- job_type options -->
                        <div class="col-md-6">
                            <label class="ul-form__label">{{ __('translate.Type_job') }} <span
                                    class="field_required">*</span></label>
                            <v-select @input="Selected_Gender" placeholder="{{ __('translate.Enter_Type_job') }}"
                                v-model="employee.job_type" :reduce="(option) => option.value" :options="
                                    [
                                        {label: 'عن بعد', value: 'Remote'},
                                        {label: 'حضوري ', value: 'In-person'},
                                        {label: 'كللاهما ', value: 'Both'},
                                    ]">
                            </v-select>

                            <span class="error" v-if="errors && errors.job_type">
                                @{{ errors.job_type[0] }}
                            </span>
                        </div>
                        
                                                <div class="col-md-6">
                            <label class="ul-form__label">{{ __('translate.Permanent_type') }} <span
                                    class="field_required">*</span></label>
                            <v-select @input="Selected_Gender" placeholder="{{ __('translate.Enter_Permanent_type') }}"
                                v-model="employee.employment_type" :reduce="(option) => option.value" :options="
                                    [
                                        {label: 'دوام كامل', value: 'Full_time'},
                                        {label: 'جزئي', value: 'Part_time'},
                                    ]">
                            </v-select>

                            <span class="error" v-if="errors && errors.employment_type">
                                @{{ errors.employment_type[0] }}
                            </span>
                        </div>
                        
                                                <div class="col-md-6">
                            <label class="ul-form__label">{{ __('translate.Employee_needs') }} <span
                                    class="field_required">*</span></label>
                            <v-select @input="Selected_Gender" placeholder="{{ __('translate.Enter_Employee_needs') }}"
                                v-model="employee.has_special_needs" :reduce="(option) => option.value" :options="
                                    [
                                        {label: 'نعم', value: '1'},
                                        {label: 'لا', value: '0'},
                                    ]">
                            </v-select>

                            <span class="error" v-if="errors && errors.has_special_needs">
                                @{{ errors.has_special_needs[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="disability_type" class="ul-form__label">{{ __('translate.Type_Disability') }}<span
                                    class="field_required">*</span></label>
                            <input type="text" class="form-control" id="disability_type"
                                placeholder="{{ __('translate.Enter_Type_Disability') }}" v-model="employee.disability_type">
                            <span class="error" v-if="errors && errors.disability_type">
                                @{{ errors.disability_type[0] }}
                            </span>
                        </div>


                        <div class="col-md-6">
                            <label class="ul-form__label">{{ __('translate.Should_police_present') }} <span
                                    class="field_required">*</span></label>
                            <v-select @input="Selected_Gender" placeholder="{{ __('translate.Enter_Should_police_present') }}"
                                v-model="employee.required_to_attend" :reduce="(option) => option.value" :options="
                                    [
                                        {label: 'نعم', value: '1'},
                                        {label: 'لا', value: '0'},
                                    ]">
                            </v-select>

                            <span class="error" v-if="errors && errors.required_to_attend">
                                @{{ errors.required_to_attend[0] }}
                            </span>
                        </div>
                        

                        <div class="form-group col-md-6">
                            <label for="Number_monthly_working_days" class="ul-form__label">{{ __('translate.Number_monthly_working_days') }} <span
                                    class="field_required">*</span></label>
                            <input type="number" class="form-control" id="Number_monthly_working_days"
                                placeholder="{{ __('translate.Enter_Number_monthly_working_days') }}" v-model="employee.monthly_working_days">
                            <span class="error" v-if="errors && errors.monthly_working_days">
                                @{{ errors.monthly_working_days[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="Number_days_annual_leave" class="ul-form__label">{{ __('translate.Number_days_annual_leave') }} <span
                                    class="field_required">*</span></label>
                            <input type="number" class="form-control" id="Number_days_annual_leave"
                                placeholder="{{ __('translate.Enter_Number_days_annual_leave') }}" v-model="employee.annual_leave_days">
                            <span class="error" v-if="errors && errors.annual_leave_days">
                                @{{ errors.annual_leave_days[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="Weekend_days" class="ul-form__label">{{ __('translate.Weekend_days') }} <span
                                    class="field_required">*</span></label>
                            <input type="number" class="form-control" id="Weekend_days"
                                placeholder="{{ __('translate.Enter_Weekend_days') }}" v-model="employee.weekend_days">
                            <span class="error" v-if="errors && errors.weekend_days">
                                @{{ errors.weekend_days[0] }}
                            </span>
                        </div>



                        <div class="col-md-6 form-group">
                            <label class="ul-form__label" for="social_enterprises_date">{{ __('translate.Date_joining_social_insurance') }}<span
                                    class="field_required">*</span></label>

                            <vuejs-datepicker id="social_enterprises_date" name="social_enterprises_date"
                                placeholder="{{ __('translate.Enter_Date_joining_social_insurance') }}" v-model="employee.social_enterprises_date"
                                input-class="form-control" format="yyyy-MM-dd"
                                @closed="employee.social_enterprises_date=formatDate(employee.social_enterprises_date)">
                            </vuejs-datepicker>

                        </div>


                        <div class="col-md-6 form-group">
                            <label class="ul-form__label" for="start_date_trial_period">{{ __('translate.Start_date_trial_period') }}<span
                                    class="field_required">*</span></label>

                            <vuejs-datepicker id="Start_date_on_platform" name="start_trial_date"
                                placeholder="{{ __('translate.Enter_Start_date_trial_period') }}" v-model="employee.start_trial_date"
                                input-class="form-control" format="yyyy-MM-dd"
                                @closed="employee.start_trial_date=formatDate(employee.start_trial_date)">
                            </vuejs-datepicker>

                        </div>
                        
                        <div class="col-md-6 form-group">
                            <label class="ul-form__label" for="end_trial_date">{{ __('translate.End_date_trial_period') }}<span
                                    class="field_required">*</span></label>

                            <vuejs-datepicker id="end_trial_date" name="end_trial_date"
                                placeholder="{{ __('translate.Enter_End_date_trial_period') }}" v-model="employee.end_trial_date"
                                input-class="form-control" format="yyyy-MM-dd"
                                @closed="employee.end_trial_date=formatDate(employee.end_trial_date)">
                            </vuejs-datepicker>

                        </div>

                        <div class="col-md-6 form-group">
                            <label class="ul-form__label" for="platform_contract_joining">{{ __('translate.Start_date_on_platform') }}<span
                                    class="field_required">*</span></label>

                            <vuejs-datepicker id="platform_contract_joining" name="platform_contract_joining"
                                placeholder="{{ __('translate.Enter_Start_date_on_platform') }}" v-model="employee.platform_contract_joining"
                                input-class="form-control" format="yyyy-MM-dd"
                                @closed="employee.platform_contract_joining=formatDate(employee.platform_contract_joining)">
                            </vuejs-datepicker>

                        </div>

                        <div class="col-md-6 form-group">
                            <label class="ul-form__label" for="platform_contract_expiry">{{ __('translate.Platform_contract_expiry') }}<span
                                    class="field_required">*</span></label>

                            <vuejs-datepicker id="platform_contract_expiry" name="platform_contract_joining"
                                placeholder="{{ __('translate.Enter_Platform_contract_expiry') }}" v-model="employee.platform_contract_expiry"
                                input-class="form-control" format="yyyy-MM-dd"
                                @closed="employee.platform_contract_expiry=formatDate(employee.platform_contract_expiry)">
                            </vuejs-datepicker>

                        </div>

                        <div class="col-md-6 form-group">
                            <label class="ul-form__label" for="medical_insurance_joining">{{ __('translate.Medical_insurance_joining') }}<span
                                    class="field_required">*</span></label>

                            <vuejs-datepicker id="medical_insurance_joining" name="medical_insurance_joining"
                                placeholder="{{ __('translate.Enter_Medical_insurance_joining') }}" v-model="employee.medical_insurance_joining"
                                input-class="form-control" format="yyyy-MM-dd"
                                @closed="employee.medical_insurance_joining=formatDate(employee.medical_insurance_joining)">
                            </vuejs-datepicker>

                        </div>

                        <div class="col-md-6 form-group">
                            <label class="ul-form__label" for="medical_insurance_expiry">{{ __('translate.Medical_insurance_expiry') }}<span
                                    class="field_required">*</span></label>

                            <vuejs-datepicker id="medical_insurance_expiry" name="medical_insurance_expiry"
                                placeholder="{{ __('translate.Enter_Medical_insurance_expiry') }}" v-model="employee.medical_insurance_expiry"
                                input-class="form-control" format="yyyy-MM-dd"
                                @closed="employee.medical_insurance_expiry=formatDate(employee.medical_insurance_expiry)">
                            </vuejs-datepicker>

                        </div>

                        
                        <div class="col-md-6 form-group">
                            <label class="ul-form__label" for="contract_expiry_date">{{ __('translate.Contract_expiry_date') }}<span
                                    class="field_required">*</span></label>

                            <vuejs-datepicker id="contract_expiry_date" name="contract_expiry_date"
                                placeholder="{{ __('translate.Enter_Contract_expiry_date') }}" v-model="employee.contract_expiry_date"
                                input-class="form-control" format="yyyy-MM-dd"
                                @closed="employee.contract_expiry_date=formatDate(employee.contract_expiry_date)">
                            </vuejs-datepicker>

                        </div>
                        
                                         <div class="col-md-6 form-group">
                            <label class="ul-form__label" for="picker3">{{ __('translate.Leaving_Date') }}</label>

                            <vuejs-datepicker id="leaving_date" name="leaving_date"
                                placeholder="{{ __('translate.Enter_Leaving_Date') }}" v-model="employee.leaving_date"
                                input-class="form-control" format="yyyy-MM-dd"
                                @closed="employee.leaving_date=formatDate(employee.leaving_date)">
                            </vuejs-datepicker>

                        </div>


                        <div class="form-group col-md-6">
                            <label for="total_leave" class="ul-form__label">{{ __('translate.Annual_Leave') }} <span
                                    class="field_required">*</span></label>
                            <input type="number" class="form-control" id="total_leave"
                                placeholder="{{ __('translate.Enter_Annual_Leave') }}" v-model="employee.total_leave">
                            <span class="error" v-if="errors && errors.total_leave">
                                @{{ errors.total_leave[0] }}
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
<script src="{{asset('assets/js/vendor/vuejs-datepicker/vuejs-datepicker.min.js')}}"></script>

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
        companies:@json($companies),
        roles: @json($roles), 
        departments: @json($departments),
        designations :@json($designations),
        office_shifts :@json($office_shifts),
        employee: @json($employee),
    },
   
   
    methods: {
        
                changeAvatar(e) {
                let file = e.target.files[0];
                this.employee.avatar = file;

            },
            
        changeSceneImage(e) {
                let file = e.target.files[0];
                this.employee.scene_image = file;

            },

        formatDate(d){
            var m1 = d.getMonth()+1;
            var m2 = m1 < 10 ? '0' + m1 : m1;
            var d1 = d.getDate();
            var d2 = d1 < 10 ? '0' + d1 : d1;
            return [d.getFullYear(), m2, d2].join('-');
        },

        Selected_Role(value) {
                if (value === null) {
                    this.employee.role_users_id = "";
                }
            },

        Selected_Company(value) {
            if (value === null) {
                this.employee.company_id = "";
                this.employee.department_id = "";
                this.employee.designation_id = "";
                this.employee.office_shift_id = "";
            }
            this.departments = [];
            this.designations = [];
            this.employee.department_id = "";
            this.employee.designation_id = "";
            this.employee.office_shift_id = "";
            this.Get_departments_by_company(value);
            this.Get_office_shift_by_company(value);
        },

        Selected_Department(value) {
            if (value === null) {
                this.employee.department_id = "";
                this.employee.designation_id = "";
            }
            this.designations = [];
            this.employee.designation_id = "";
            this.Get_designations_by_department(value);
        },


        Selected_Designation(value) {
            if (value === null) {
                this.employee.designation_id = "";
            }
        },

        Selected_Gender(value) {
            if (value === null) {
                this.employee.gender = "";
            }
        },

        
        Selected_Office_shift(value) {
            if (value === null) {
                this.employee.office_shift_id = "";
            }
        },


        
        //---------------------- Get_departments_by_company ------------------------------\\
        Get_departments_by_company(value) {
        axios
            .get("/core/Get_departments_by_company?id=" + value)
            .then(({ data }) => (this.departments = data));
        },

        //---------------------- Get designations by department ------------------------------\\
        Get_designations_by_department(value) {
        axios
            .get("/core/get_designations_by_department?id=" + value)
            .then(({ data }) => (this.designations = data));
        },

         //---------------------- Get_office_shift_by_company ------------------------------\\
         Get_office_shift_by_company(value) {
        axios
            .get("/Get_office_shift_by_company?id=" + value)
            .then(({ data }) => (this.office_shifts = data));
        },

        //------------------------ Update Employee ---------------------------\\
        Update_Employee() {
            var self = this;
            self.SubmitProcessing = true;
            
            axios.put("/employees/" + self.employee.id,{
                firstname: self.employee.firstname,
                lastname: self.employee.lastname,
                country: self.employee.country,
                email: self.employee.email,
                gender: self.employee.gender,
                phone: self.employee.phone,
                birth_date: self.employee.birth_date,
                company_id: self.employee.company_id,
                department_id: self.employee.department_id,
                designation_id: self.employee.designation_id,
                office_shift_id: self.employee.office_shift_id,
                joining_date: self.employee.joining_date,
                password: self.employee.password,
                password_confirmation: self.employee.password_confirmation,
                role_users_id: self.employee.role_users_id,
                third_name: self.employee.third_name,
                fourth_name: self.employee.fourth_name,
                start_trial_date: self.employee.start_trial_date,
                end_trial_date: self.employee.end_trial_date,
                job_description: self.employee.job_description,
                language_level: self.employee.language_level,
                specialization: self.employee.specialization,
                educational_qualification: self.employee.educational_qualification,
                supervisor_name: self.employee.supervisor_name,
                social_enterprises_date: self.employee.social_enterprises_date,
                contract_expiry_date: self.employee.contract_expiry_date,
                medical_insurance_joining: self.employee.medical_insurance_joining,
                medical_insurance_expiry: self.employee.medical_insurance_expiry,
                platform_contract_joining: self.employee.platform_contract_joining,
                platform_contract_expiry: self.employee.platform_contract_expiry,
                date_of_birth_hijri: self.employee.date_of_birth_hijri,
                weekend_days: self.employee.weekend_days,
                annual_leave_days: self.employee.annual_leave_days,
                monthly_working_days: self.employee.monthly_working_days,
                required_to_attend: self.employee.required_to_attend,
                marital_status: self.employee.marital_status,
                has_special_needs: self.employee.has_special_needs,
                disability_type: self.employee.disability_type,
                scene_image: self.employee.scene_image,
                job_type: self.employee.job_type,
                contract_type: self.employee.contract_type,
                job_title: self.employee.job_title,
                avatar: self.employee.avatar,
                leaving_date: self.employee.leaving_date,
                exit_date: self.employee.exit_dat,
                total_leave: self.employee.total_leave,
            }).then(response => {
                    self.SubmitProcessing = false;
                    window.location.href = '/employees'; 
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