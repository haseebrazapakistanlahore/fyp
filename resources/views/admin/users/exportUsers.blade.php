<html>
<style>
    table {
        border: 1px solid #eee;
        padding: 0px;
        text-align: center;
        font-family: sans-serif;
    }
    h1{
        font-family: sans-serif;
    }
    table th, table td {
        padding: 5px;
    }
    table{
        box-sizing: border-box;
        margin: 0;
    }
    table tr td{
        font-size: 12px;
        text-transform: capitalize;
        border-top: solid 1px #eee;
        border-right: solid 1px #eee;
    }
    table tr td:last-child{
        border-right: 0;
    }
    th {
        color: white;
        background-color: #337ab7;
        font-size: 14px;
    }
</style>
<body>

    <h1> {{$title}} </h1>
    <hr>

    <table width="100%">
        <thead>
            <tr>
                <th>Sr. No.</th>
                {{-- <th>First Name</th>
                <th>Last Name</th> --}}
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
        </thead>
        @php $count=1; @endphp
        @foreach($users as $user)
        <tbody>
            <tr class="odd gradeX">
                <td class="center">{{$count++}}</td>
                {{-- <td class="center">{{$user->first_name}}</td> --}}
                {{-- <td class="center">{{$user->last_name}}</td> --}}
                <td class="center">{{$user->full_name}}</td>
                <td class="center">{{$user->email}}</td>
                <td class="center">{{$user->phone}}</td>
            </tr>
        </tbody>
        @endforeach
    </table>

</body>
</html>