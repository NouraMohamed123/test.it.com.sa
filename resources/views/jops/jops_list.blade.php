@extends('layouts.master')
@section('main-content')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">

@endsection

<div class="breadcrumb">
    <h1>{{ __('translate.Employee_List') }}</h1>
    <ul>
        <li><a href="/employees">{{ __('translate.Employees') }}</a></li>
        <li>{{ __('translate.Employee_List') }}</li>
    </ul>
</div>

<div class="separator-breadcrumb border-top"></div>

<div class="row" id="section_Employee_list">
    <div class="col-md-12">
        <div class="card text-left">
            <div class="card-header text-right bg-transparent">
                @can('employee_add')
                <a class="btn btn-primary btn-md m-1" href="{{route('jops.create')}}"><i
                        class="i-Add-User text-white mr-2"></i> {{ __('translate.Create') }}</a>
                @endcan
                @can('employee_delete')
                <a v-if="selectedIds.length > 0" class="btn btn-danger btn-md m-1" @click="delete_selected()"><i
                        class="i-Close-Window text-white mr-2"></i> {{ __('translate.Delete') }}</a>
                @endcan
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table id="employee_list_table" class="display table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>{{ __('translate.job_title') }}</th>
                                <th>{{ __('translate.required_candidates') }}</th>
                                <th>{{ __('translate.work_area') }}</th>
                                <th>{{ __('translate.city') }}</th>
                                <th>{{ __('translate.academic_qualification') }}</th>
                                <th>{{ __('translate.language_level') }}</th>
                                <th>{{ __('translate.work_type') }}</th>
                                <th>{{ __('translate.working_hours') }}</th>
                                <th>{{ __('translate.basic_salary') }}</th>
                                <th>{{ __('translate.Action') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($jops as $jop)
                            <tr>
                                <td></td>
                                <td @click="selected_row( {{ $jop->id}})"></td>
                                <td>{{$jop->job_title}}</td>
                                <td>{{$jop->required_candidates}}</td>
                                <td>{{$jop->work_area}}</td>
                                <td>{{$jop->city}}</td>
                                <td>{{$jop->academic_qualification}}</td>
                                <td>{{$jop->language_level}}</td>
                                <td>{{$jop->work_type}}</td>
                                <td>{{$jop->working_hours}}</td>
                                <td>{{$jop->basic_salary}}</td>

                                <td>
                                    @can('employee_details')
                                    <a href="/jops/{{$jop->id}}" class="ul-link-action text-info"
                                        data-toggle="tooltip" data-placement="top" title="Show">
                                        <i class="i-Eye"></i>
                                    </a>
                                    @endcan

                                    @can('employee_edit')
                                    <a href="/jops/{{$jop->id}}}/edit" class="ul-link-action text-success"
                                        data-toggle="tooltip" data-placement="top" title="Edit">
                                        <i class="i-Edit"></i>
                                    </a>
                                    @endcan

                                    @can('employee_delete')
                                    <a @click="Remove_Employee( {{ $jop->id}})"
                                        class="ul-link-action text-danger mr-1" data-toggle="tooltip"
                                        data-placement="top" title="Delete">
                                        <i class="i-Close-Window"></i>
                                    </a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>


<script>
    var app = new Vue({
        el: '#section_Employee_list',
        data: {
            SubmitProcessing:false,
            selectedIds:[],
        },

        methods: {

            //---- Event selected_row
            selected_row(id) {
                //in here you can check what ever condition  before append to array.
                if(this.selectedIds.includes(id)){
                    const index = this.selectedIds.indexOf(id);
                    this.selectedIds.splice(index, 1);
                }else{
                    this.selectedIds.push(id)
                }
            },

            //--------------------------------- Remove Employee ---------------------------\\
            Remove_Employee(id) {

                swal({
                    title: '{{ __('translate.Are_you_sure') }}',
                    text: '{{ __('translate.You_wont_be_able_to_revert_this') }}',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0CC27E',
                    cancelButtonColor: '#FF586B',
                    confirmButtonText: '{{ __('translate.Yes_delete_it') }}',
                    cancelButtonText: '{{ __('translate.No_cancel') }}',
                    confirmButtonClass: 'btn btn-primary mr-5',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false
                }).then(function () {
                        axios
                            .delete("/jops/" + id)
                            .then(() => {
                                window.location.href = '/jops';
                                toastr.success('{{ __('translate.Deleted_in_successfully') }}');

                            })
                            .catch(() => {
                                toastr.error('{{ __('translate.There_was_something_wronge') }}');
                            });
                    });
                },



            //--------------------------------- delete_selected ---------------------------\\
            delete_selected() {
                var self = this;
                swal({
                    title: '{{ __('translate.Are_you_sure') }}',
                    text: '{{ __('translate.You_wont_be_able_to_revert_this') }}',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0CC27E',
                    cancelButtonColor: '#FF586B',
                    confirmButtonText: '{{ __('translate.Yes_delete_it') }}',
                    cancelButtonText: '{{ __('translate.No_cancel') }}',
                    confirmButtonClass: 'btn btn-primary mr-5',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false
                }).then(function () {
                        axios
                        .post("/jops/delete/by_selection", {
                            selectedIds: self.selectedIds
                        })
                            .then(() => {
                                window.location.href = '/jops';
                                toastr.success('{{ __('translate.Deleted_in_successfully') }}');

                            })
                            .catch(() => {
                                toastr.error('{{ __('translate.There_was_something_wronge') }}');
                            });
                    });
            },







        },
        //-----------------------------Autoload function-------------------
        created() {
        }

    })

</script>

<script type="text/javascript">
    $(function () {
        "use strict";

        $('#employee_list_table').DataTable({
            "processing": true,
            select: {
                style: 'multi',
                selector: '.select-checkbox',
                items: 'row',
            },
            responsive: {
                details: {
                    type: 'column',
                    target: 0
                }
            },
            columnDefs: [{
                    targets: 0,
                    className: 'control'
                },
                {
                    targets: 1,
                    className: 'select-checkbox'
                },
                {
                    targets: [0, 1],
                    orderable: false
                }
            ],

            dom: "<'row'<'col-sm-12 col-md-7'lB><'col-sm-12 col-md-5 p-0'f>>rtip",
            oLanguage: {
                sLengthMenu: "_MENU_",
                sSearch: '',
                sSearchPlaceholder: "{{__('pagination.searchPlaceholder')}}",
                oPaginate: {
                    sNext: "{{__('pagination.next')}}",
                    sPrevious: "{{__('pagination.previous')}}",
                },
                sInfo: "{{__('pagination.info')}}",
                sInfoEmpty: "{{__('pagination.infoEmpty')}}",
                sInfoFiltered: "{{__('pagination.infoFiltered')}}"
            },
            buttons: [{
                extend: 'collection',
                text: "{{__('pagination.exports')}}",
                buttons: [
                    {
                        extend: 'csv',
                        text: 'CSV',
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                    },
                    {
                        extend: 'print',
                        text: "{{__('pagination.prints')}}",
                    }
                ]
            }]
        });
    });
</script>
@endsection
