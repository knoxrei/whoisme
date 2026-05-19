@extends('errors.minimal')

@section('title', 'Session Expired')
@section('code', '419')
@section('message', 'Security token mismatch or session timeout. Please refresh and try again.')
