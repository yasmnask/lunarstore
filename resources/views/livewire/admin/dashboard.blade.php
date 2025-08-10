<div>
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div class="page-heading">
        <h3>Lunar Store Dashboard</h3>
        <p class="text-subtitle text-muted">Digital products and game top-up analytics</p>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-12">
                <!-- Stats Cards Row -->
                <div class="row">
                    <!-- Total Users Card -->
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon purple mb-2">
                                            <i class="bi bi-people-fill"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Users</h6>
                                        <h6 class="font-extrabold mb-0">{{ number_format($totalUsers) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Products Card -->
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon blue mb-2">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Apps</h6>
                                        <h6 class="font-extrabold mb-0">{{ number_format($totalProducts) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Categories Card -->
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon green mb-2">
                                            <i class="bi bi-tags-fill"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Categories</h6>
                                        <h6 class="font-extrabold mb-0">{{ number_format($totalCategories) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Revenue Card -->
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon red mb-2">
                                            <i class="bi bi-currency-dollar"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Revenue</h6>
                                        <h6 class="font-extrabold mb-0">Rp
                                            {{ number_format($totalRevenue, 0, ',', '.') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Product Types Distribution</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="product-types-chart" style="min-height: 250px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Best-Seller Products</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="best-products-chart" style="min-height: 250px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Trend Chart -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>Sales Overview</h4>
                                <div class="card-header-action">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-primary active"
                                            data-chart-type="sales">Sales</button>
                                        <button type="button" class="btn btn-outline-primary"
                                            data-chart-type="users">Users</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="sales-trend-chart" style="min-height: 365px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tables Row -->
                <div class="row">
                    <!-- Popular Categories -->
                    <div class="col-12 col-xl-8">
                        <div class="card">
                            <div class="card-header">
                                <h4>Popular Categories</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-lg">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Products</th>
                                                <th>Popularity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($popularCategories as $category)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-md me-3">
                                                                @if ($category['image'])
                                                                    <img src="{{ asset('uploads/categories/' . $category['image']) }}"
                                                                        alt="{{ $category['title'] }}">
                                                                @else
                                                                    <div class="avatar-content">
                                                                        {{ substr($category['title'], 0, 1) }}</div>
                                                                @endif
                                                            </div>
                                                            <p class="font-bold mb-0">{{ $category['title'] }}</p>
                                                        </div>
                                                    </td>
                                                    <td>{{ number_format($category['product_count']) }}</td>
                                                    <td>
                                                        <div class="progress" style="height: 7px;">
                                                            <div class="progress-bar bg-success" role="progressbar"
                                                                style="width: {{ $category['percentage'] }}%"
                                                                aria-valuenow="{{ $category['percentage'] }}"
                                                                aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="col-12 col-xl-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Recent Transactions</h4>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    @foreach ($recentTransactions as $transaction)
                                        <div class="list-group-item d-flex justify-content-between align-items-start">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold">{{ $transaction->transaction_code }}</div>
                                                <small class="text-muted">{{ $transaction->user->name }}</small>
                                            </div>
                                            <div class="text-end">
                                                <span
                                                    class="badge bg-{{ $transaction->payment_status === 'paid' ? 'success' : 'warning' }} rounded-pill">
                                                    {{ ucfirst($transaction->payment_status) }}
                                                </span>
                                                <div class="small text-muted">{{ $transaction->formatted_total }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Latest Products -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Latest Products</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('admin.products') }}" class="btn btn-primary">View All</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>App Name</th>
                                                <th>Category</th>
                                                <th>Added</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($latestProducts as $product)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-md me-3">
                                                                @if ($product->cover_img)
                                                                    <img src="{{ asset('uploads/products/' . $product->cover_img) }}"
                                                                        alt="{{ $product->app_name }}">
                                                                @else
                                                                    <div class="avatar-content">
                                                                        {{ substr($product->app_name, 0, 1) }}</div>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <p class="font-bold mb-0">{{ $product->app_name }}</p>
                                                                <p class="text-muted mb-0 small">
                                                                    {{ Str::limit($product->description, 30) }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $product->category->title }}</td>
                                                    <td>{{ $product->created_at->diffForHumans() }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.products') }}"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No products found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', function() {
            // Sales Trend Chart
            const salesTrendCtx = document.getElementById('sales-trend-chart').getContext('2d');
            const salesTrendChart = new Chart(salesTrendCtx, {
                type: 'line',
                data: {
                    labels: @json($salesTrend['labels']),
                    datasets: [{
                        label: 'Revenue (Rp)',
                        data: @json($salesTrend['data']),
                        fill: true,
                        backgroundColor: 'rgba(67, 94, 190, 0.2)',
                        borderColor: 'rgba(67, 94, 190, 1)',
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(67, 94, 190, 1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(67, 94, 190, 1)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            padding: 10,
                            titleColor: '#fff',
                            titleFont: {
                                size: 14
                            },
                            bodyColor: '#fff',
                            bodyFont: {
                                size: 14
                            },
                            callbacks: {
                                label: function(context) {
                                    return 'Revenue: Rp ' + new Intl.NumberFormat('id-ID').format(
                                        context.raw);
                                }
                            }
                        }
                    }
                }
            });

            // Product Types Chart (Doughnut Chart)
            const productTypesCtx = document.getElementById('product-types-chart').getContext('2d');
            const productTypesChart = new Chart(productTypesCtx, {
                type: 'doughnut',
                data: {
                    labels: @json(array_column($productTypes, 'type_name')),
                    datasets: [{
                        data: @json(array_column($productTypes, 'count')),
                        backgroundColor: [
                            'rgba(67, 94, 190, 0.8)',
                            'rgba(46, 204, 113, 0.8)',
                            'rgba(231, 76, 60, 0.8)',
                            'rgba(241, 196, 15, 0.8)',
                            'rgba(155, 89, 182, 0.8)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Best Products Chart (Bar Chart)
            const bestProductsCtx = document.getElementById('best-products-chart').getContext('2d');
            const bestProductsChart = new Chart(bestProductsCtx, {
                type: 'bar',
                data: {
                    labels: @json(array_column($bestProducts, 'app_name')),
                    datasets: [{
                        label: 'Units Sold',
                        data: @json(array_column($bestProducts, 'total_sold')),
                        backgroundColor: 'rgba(67, 94, 190, 0.8)',
                        borderColor: 'rgba(67, 94, 190, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Toggle between sales and users chart
            document.querySelectorAll('[data-chart-type]').forEach(button => {
                button.addEventListener('click', function() {
                    const chartType = this.dataset.chartType;

                    // Update active state
                    document.querySelectorAll('[data-chart-type]').forEach(btn => btn.classList
                        .remove('active'));
                    this.classList.add('active');

                    // Call Livewire method to get new data
                    @this.call('getChartData', chartType).then(response => {
                        salesTrendChart.data.labels = response.labels;
                        salesTrendChart.data.datasets[0].label = chartType === 'sales' ?
                            'Revenue (Rp)' : 'New Users';
                        salesTrendChart.data.datasets[0].data = response.data;
                        salesTrendChart.update();
                    });
                });
            });
        });
    </script>
@endpush
