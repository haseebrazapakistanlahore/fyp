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

    <h1> {{ $title }} </h1>
    <hr>

    <table width="100%">
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Product Title</th>
                <th>Category</th>
                <th>SubCategory</th>
                <th>Type</th>
                <th>Consumer Price</th>
                <th>Professional Price</th>
                <th>No. of Orders</th>
                <th>No. of Products</th>
            </tr>
        </thead>
        @php $count=1; @endphp
        @foreach($products as $product)
        <tbody>
            <tr class="odd gradeX">
                <td>{{ $count++ }}</td>
                <td>{{ $product->product->title }}</td>
                <td>{{ $product->product->category->title }}</td>
                <td>{{ isset($product->product->subCategory) ? $product->product->subCategory->title : '- -' }}</td>
                @if ($product->product->product_type == '0')
                <td>Consumer</td>
                @endif
                @if ($product->product->product_type == '1')
                <td>Professional</td>
                @endif
                @if ($product->product->product_type == '2')
                <td>Consumer / Professional</td>
                @endif
                <td>{{$product->product->price}}</td>
                <td>{{$product->product->price_for_professional}}</td>
                <td>{{ $product->orderDetailCount }}</td>
                <td>{{ $product->totalQuantity }}</td>
            </tr>
        </tbody>
        @endforeach
    </table>

</body>

</html>