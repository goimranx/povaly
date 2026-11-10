@extends(EcommerceHelper::viewPath('customers.layouts.account-settings'))

@section('title', __('Change Password'))

@section('account-content')
    {!! $form->renderForm() !!}
@stop
