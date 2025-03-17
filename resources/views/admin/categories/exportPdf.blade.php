<html>
<style>
    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
        padding: 10px;
    }
    th{
        color:white;
        background-color: grey;
    }
}
</style>
<body>

    <h1> Category List </h1>
    <hr>

    <table width="100%">
        <thead>
            <tr>
                <th>Sr. No</th>
                <th>Title</th>
            </tr>
        </thead>
        <tbody>@php $count=1; @endphp
        @foreach($categories as $category)
            <tr class="odd gradeX">
                <td class="center">{{$count++}}</td>
                <td class="center">{{$category->title}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</body>
</html>
