<li class="nav-item"><a href="{{route('home')}}" class="nav-link"><span class="pcoded-micon">
    <i class="fas fa-tachometer-alt"></i></span><span class="pcoded-mtext">Dashboard</span></a>
</li>
@can('View Sales Management')
<li class="nav-item pcoded-hasmenu">
    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-money-check-alt"></i></span>
        <span class="pcoded-mtext">Sales</span>
    </a>
    <ul class="pcoded-submenu">
        @can('Cash Sales')
        <li class=""><a href="{{route('cash-sales.cashSale')}}" class="">Point of Sale</a></li>
        @endcan
        @can('View Sales History')
        <li class=""><a href="{{route('sale-histories.SalesHistory')}}" class="">Sales History</a></li>
        @endcan
         @can('Sales Return')
        <li class=""><a href="{{route('sale-returns.index')}}" class="">Sales Return</a></li>
        @endcan

    </ul>
</li>
@endcan


<li class="nav-item pcoded-hasmenu">
    @can('View Inventory Management')
        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-dolly"></i></span>
            <span class="pcoded-mtext">Inventory</span>
        </a>
        <ul class="pcoded-submenu">
            @can('View Current Stock')
                <li class=""><a href="{{ route('current-stock.index') }}" class="">Current Stock</a></li>
            @endcan
            @can('View Price List')
                <li class=""><a href="{{ route('price-list.index') }}" class="">Price List</a></li>
            @endcan
            @can('View Stock Adjustment')
                <li class=""><a href="{{ route('stock-adjustment.index') }}" class="">Adjustment History</a></li>
            @endcan

            @can('View Daily Stock Count')
                <li class=""><a href="{{ route('daily-stock-count.index') }}" class="">Daily Stock Count</a></li>
            @endcan
            @can('View Inventory Count Sheet')
                <li class=""><a href="{{ route('inventory-count-sheet-pdf-gen') }}" target="_blank" class="">Inventory
                        Count
                        Sheet</a></li>
            @endcan
            @can('Stock Transfer')
                <li class=""><a href="{{ route('stock-transfer-history') }}" class="">Stock Transfer</a></li>
            @endcan
            @can('View Stock Transfer History')
                <li class=""><a href="{{ route('stock-transfer-reprint.index') }}" class="">Transfer History</a></li>
            @endcan

        </ul>
    @endcan
</li>

<li class="nav-item pcoded-hasmenu">
    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-shopping-cart"></i></span>
        <span class="pcoded-mtext">Purchases</span>
    </a>
    <ul class="pcoded-submenu">
        <li class=""><a href="{{route('goods-receiving.index')}}" class="">Goods Receiving</a></li>
        <li class=""><a href="{{route('material-received.index')}}" class="">Material Received</a></li>


    </ul>
</li>

<li class="nav-item pcoded-hasmenu">
    @can('View Expense Management')
        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-dollar-sign"></i></span>
            <span class="pcoded-mtext">Expenses</span>
        </a>
        @can('View Expenses')
            <ul class="pcoded-submenu">
                <li class=""><a href="{{route('expense.index')}}" class="">Expenses</a></li>
            </ul>
        @endcan
    @endcan
</li>

<li data-username="Vertical Horizontal Box Layout RTL fixed static collapse menu color icon dark"
    class="nav-item pcoded-hasmenu">
    @can('View Reports')
        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-file-pdf"></i></span><span
                class="pcoded-mtext">Reports</span></a>
        <ul class="pcoded-submenu">
              @can('View Sales Reports')
            <li class=""><a href="{{route('sale-report-index')}}" class="">Sales Reports</a></li>
            @endcan
            @can('View Inventory Reports')
                <li class=""><a href="{{route('inventory-report-index')}}" class="">Inventory Reports</a></li>
            @endcan
            <li class=""><a href="{{route('purchase-report-index')}}" class="">Purchase Reports</a>
            </li>
        </ul>
    @endcan
</li>


<li class="nav-item pcoded-hasmenu">
    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-users"></i></span>
        <span class="pcoded-mtext">Users</span>
    </a>
    <ul class="pcoded-submenu">
        <li class=""><a href="{{route('roles.index')}}" class="">Roles</a></li>
        <li class=""><a href="{{route('users.index')}}" class="">Users</a></li>
    </ul>
</li>

<li class="nav-item pcoded-hasmenu">
    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-stream"></i></span>
        <span class="pcoded-mtext">Masters</span>
    </a>
    <ul class="pcoded-submenu">
        <li class=""><a href="{{route('categories.index')}}" class="">Product Categories</a></li>
        <li class=""><a href="{{route('products.index')}}" class="">Products</a></li>
        <li class=""><a href="{{route('price-categories.index')}}" class="">Price Categories</a></li>
        <li class=""><a href="{{route('expense-categories.index')}}" class="">Expense Categories</a></li>
        <li class=""><a href="{{route('adjustment-reasons.index')}}" class="">Adjustment Reasons</a></li>
        <li class=""><a href="{{route('suppliers.index')}}" class="">Suppliers</a></li>
        <li class=""><a href="{{route('stores.index')}}" class="">Stores</a></li>
    </ul>

</li>
<li class="nav-item"><a href="{{route('configurations.index')}}" class="nav-link"><span class="pcoded-micon"><i
                class="feather icon-settings"></i></span><span class="pcoded-mtext">Settings</span></a>
</li>



