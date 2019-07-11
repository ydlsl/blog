@extends('test.layout')

@section('content')

    <div >

        <table>
            <thead>
                <tr>
                    <th>id</th>
                    <th>name</th>
                    <th>age</th>
                    <th>firstTime</th>
                    <th>lastTime</th>
                </tr>
            </thead>
            <tbody>
            @foreach($info as $infos)
                <tr>
                    <td>{{$infos->id}}</td>
                    <td>{{$infos->name}}</td>
                    <td>{{$infos->age}}</td>
                    <td>{{$infos->created_at }}</td>
                    <td>{{$infos->updated_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>


    </div>

@stop