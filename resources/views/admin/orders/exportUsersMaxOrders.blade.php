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
                <th>Full Name</th>
                <th>User Type</th>
                <th>Email</th>
                <th>Phone</th>
                <th>No. of Orders</th>
            </tr>
        </thead>
        <tbody>@php $count=1; @endphp
        @foreach($users as $user)
            <tr class="odd gradeX">
                <td>{{$count++}}</td>
                <td>{{$user->consumer->full_name}}</td>
                <td>Consumer</td>
                <td>{{$user->consumer->email}}</td>
                <td>{{$user->consumer->phone}}</td>
                <td>{{$user->orderCount}}</td>  
            </tr>
        @endforeach
        </tbody>
    </table>

</body>
</html>
