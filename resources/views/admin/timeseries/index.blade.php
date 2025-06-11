@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-1">{{ __('Time Series Analytics') }}</h5>
            <small class="text-muted">Visualize trends, seasonality, and variations in patient application categories over
                time.</small>
        </div>

        <div class="card-body">
            <div class="row mb-3 align-items-center">
                <div class="col-md-4">
                    <input type="text" id="dateRangePicker" class="form-control" placeholder="Select Date Range" readonly>
                </div>
                <div class="col-md-4">
                    <select id="caseCategorySelector" class="form-control"></select>
                </div>
                <div class="col-md-4 text-end">
                    <button id="downloadChart" class="btn btn-outline-primary">Download Chart</button>
                </div>
            </div>


            <div id="loadingSpinner" style="display: none; text-align:center; padding: 40px;">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <canvas id="timeSeriesChart" height="100" style="display:none;"></canvas>

            @include('admin.timeseries.report')
        </div>
        <div class="card-header">
            <h5 class="mb-1">{{ __('Statistical Analysis') }}</h5>
            <small class="text-muted">Visualize mean, media, mode, variance, standard deviation of applicant age</small>
            <div class="card-body">
                <div class="row mb-3 align-items-center">
                    @include('admin.timeseries.age_statistics')
                </div>
            </div>

        </div>
    </div>

@endsection

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script>
        let chart;
        const loadingSpinner = document.getElementById('loadingSpinner');
        const canvas = document.getElementById('timeSeriesChart');
        const selector = document.getElementById('caseCategorySelector');

        const summaryContainer = document.getElementById('summaryReport');
        const summaryContent = document.getElementById('summaryContent');

        let fullData = {};
        let selectedStartDate, selectedEndDate;

        loadingSpinner.style.display = 'block';
        canvas.style.display = 'none';
        selector.disabled = true;
        summaryContainer.style.display = 'none';

        $(document).ready(function () {
            $(selector).select2({
                placeholder: "Select a Case Category",
                width: 'resolve'
            });

            setTimeout(() => {
                fetchDataAndRender();
            }, 10);

            $('#dateRangePicker').daterangepicker({
                locale: {
                    format: 'MMMM YYYY'
                },
                showDropdowns: true,
                minYear: 2020,
                maxYear: parseInt(moment().format('YYYY'), 10),
                opens: 'left',
                autoUpdateInput: false,
                startDate: moment().startOf('month'),
                endDate: moment().endOf('month'),
                ranges: {
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [
                        moment().subtract(1, 'year').startOf('year'),
                        moment().subtract(1, 'year').endOf('year')
                    ]
                }
            }, function (start, end, label) {
                selectedStartDate = start;
                selectedEndDate = end;
                $('#dateRangePicker').val(start.format('MMMM YYYY') + ' - ' + end.format('MMMM YYYY'));
                filterByDateRange();
            });

        });

        function fetchDataAndRender() {
            fetch("{{ asset('storage/stl_output.json') }}")
                .then(res => res.json())
                .then(data => {
                    const categories = Object.keys(data);
                    fullData = data;


                    // Populate selector
                    categories.forEach(cat => {
                        const option = document.createElement('option');
                        option.value = cat;
                        option.textContent = cat;
                        selector.appendChild(option);
                    });

                    $(selector).val(categories[0]).trigger('change');
                    renderChart(categories[0], data);

                    selector.addEventListener('change', (e) => {
                        loadingSpinner.style.display = 'block';
                        canvas.style.display = 'none';
                        selector.disabled = true;
                        summaryContainer.style.display = 'none';

                        setTimeout(() => {
                            renderChart(e.target.value, data);
                        }, 300);
                    });

                    document.getElementById('downloadChart').addEventListener('click', () => {
                        const link = document.createElement('a');
                        link.href = chart.toBase64Image();
                        link.download = `${selector.value}_time_series_chart.png`;
                        link.click();
                    });
                });
        }

        function renderChart(category, data) {
            const dataset = data[category];
            const ctx = canvas.getContext('2d');
            if (chart) chart.destroy();

            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dataset.dates,
                    datasets: [{
                        label: 'Observed',
                        data: dataset.observed,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Trend',
                        data: dataset.trend,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Seasonal',
                        data: dataset.seasonal,
                        borderColor: 'rgba(255, 206, 86, 1)',
                        backgroundColor: 'rgba(255, 206, 86, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Residual',
                        data: dataset.residual,
                        borderColor: 'rgba(153, 102, 255, 1)',
                        backgroundColor: 'rgba(153, 102, 255, 0.1)',
                        fill: true,
                        tension: 0.4
                    }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    },
                    plugins: {
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        },
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Month'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Applications'
                            }
                        }
                    }
                }
            });

            loadingSpinner.style.display = 'none';
            canvas.style.display = 'block';
            selector.disabled = false;

            generateSummary(dataset);
        }

        function filterByDateRange() {
            if (!chart || !selectedStartDate || !selectedEndDate) return;

            const category = selector.value;
            const dataset = fullData[category];
            const filteredDates = [];
            const filteredObserved = [];
            const filteredTrend = [];
            const filteredSeasonal = [];
            const filteredResidual = [];

            dataset.dates.forEach((dateStr, index) => {
                const date = moment(dateStr, 'YYYY-MM');
                if (date.isBetween(selectedStartDate.clone().startOf('month'), selectedEndDate.clone().endOf(
                    'month'), null, '[]')) {
                    filteredDates.push(dateStr);
                    filteredObserved.push(dataset.observed[index]);
                    filteredTrend.push(dataset.trend[index]);
                    filteredSeasonal.push(dataset.seasonal[index]);
                    filteredResidual.push(dataset.residual[index]);
                }
            });

            chart.data.labels = filteredDates;
            chart.data.datasets[0].data = filteredObserved;
            chart.data.datasets[1].data = filteredTrend;
            chart.data.datasets[2].data = filteredSeasonal;
            chart.data.datasets[3].data = filteredResidual;
            chart.update();

            generateSummary({
                dates: filteredDates,
                observed: filteredObserved,
                trend: filteredTrend,
                seasonal: filteredSeasonal,
                residual: filteredResidual
            });
        }


        function generateSummary(dataset) {
            const avg = arr => arr.reduce((a, b) => a + b, 0) / arr.length;
            const max = arr => Math.max(...arr);
            const min = arr => Math.min(...arr);

            const observed = dataset.observed;
            const trend = dataset.trend;
            const seasonal = dataset.seasonal;
            const residual = dataset.residual;
            const dates = dataset.dates;

            const average = avg(observed).toFixed(2);

            // PEAK: Get max value and all months with that value
            const maxValue = max(observed);
            const peakMonths = observed
                .map((val, i) => (val === maxValue ? dates[i] : null))
                .filter(date => date !== null);

            // LOWEST (non-zero): Filter out zero values, then get min and all months with that value
            const nonZeroObserved = observed
                .map((val, i) => ({ val, i }))
                .filter(item => item.val > 0);

            let minValue, lowMonths;
            if (nonZeroObserved.length > 0) {
                minValue = Math.min(...nonZeroObserved.map(item => item.val));
                lowMonths = nonZeroObserved
                    .filter(item => item.val === minValue)
                    .map(item => dates[item.i]);
            } else {
                minValue = 0;
                lowMonths = observed
                    .map((val, i) => (val === 0 ? dates[i] : null))
                    .filter(date => date !== null);
            }

            // Trend interpretation
            const trendDirection = trend[trend.length - 1] - trend[0];
            let trendInsight = '';
            if (trendDirection > 5) {
                trendInsight = 'There is a steady increase in applications over time.';
            } else if (trendDirection < -5) {
                trendInsight = 'There is a noticeable decline in applications over time.';
            } else {
                trendInsight = 'Applications have remained mostly stable over the selected period.';
            }

            // Seasonal interpretation
            const seasonalRange = max(seasonal) - min(seasonal);
            const seasonalityInsight = seasonalRange > 5
                ? 'There are clear seasonal effects—certain months consistently have more applications.'
                : 'Seasonal patterns are mild or negligible.';

            // Residual interpretation
            const residualMax = Math.max(...residual.map(Math.abs));
            const residualInsight = residualMax > 10
                ? 'There are large short-term fluctuations in the data, possibly due to irregular events.'
                : 'Short-term changes are small, indicating consistent application trends.';

            // Format multiple months
            const formatMonths = months => months.join(', ');

            summaryContent.innerHTML = `
            <li class="list-group-item">
                <strong>Average Applications per Month:</strong> ${average}
            </li>
            <li class="list-group-item">
                <strong>Peak Applications:</strong> ${maxValue} in ${formatMonths(peakMonths)}
            </li>
            <li class="list-group-item">
                <strong>Lowest Applications:</strong> ${minValue} in ${formatMonths(lowMonths)}
            </li>
            <li class="list-group-item">
                <strong>Trend Insight:</strong> ${trendInsight}
            </li>
            <li class="list-group-item">
                <strong>Seasonality Insight:</strong> ${seasonalityInsight}
            </li>
            <li class="list-group-item">
                <strong>Residual Insight:</strong> ${residualInsight}
            </li>
        `;

            summaryContainer.style.display = 'block';
        }




        function interpretTrend(trend) {
            const start = trend[0];
            const end = trend[trend.length - 1];
            if (end > start) return "Upward trend observed.";
            else if (end < start) return "Downward trend observed.";
            else return "Stable trend.";
        }

        document.addEventListener('DOMContentLoaded', () => {
            const yearSelector = document.getElementById('ageStatsYear');
            const loadingSpinner = document.getElementById('ageStatsLoading');

            function fetchAgeStats(year) {
                loadingSpinner.style.display = 'inline-block';
                fetch(`{{ route('admin.analytics.age-stats') }}?year=${year}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.message === 'No age data for selected year.') {
                            throw new Error(data.message);
                        }

                        loadingSpinner.style.display = 'none';
                        const list = document.getElementById('ageStats');


                        // Define age group based on median
                        let ageGroup;
                        if (data.median < 18) {
                            ageGroup = 'children or teens';
                        } else if (data.median < 30) {
                            ageGroup = 'young adults';
                        } else if (data.median < 60) {
                            ageGroup = 'middle-aged adults';
                        } else {
                            ageGroup = 'older adults';
                        }

                        // Interpret variance
                        let varianceDescription;
                        if (data.variance < 100) {
                            varianceDescription = 'very little variation — most applicants are around the same age';
                        } else if (data.variance < 1000) {
                            varianceDescription = 'some variation — applicant ages differ moderately';
                        } else {
                            varianceDescription = 'a wide spread — applicants come from many different age groups';
                        }

                        // Interpret standard deviation
                        let sdDescription;
                        if (data.standard_deviation < 10) {
                            sdDescription = 'most ages are tightly clustered around the average';
                        } else if (data.standard_deviation < 30) {
                            sdDescription = 'ages vary moderately around the average';
                        } else {
                            sdDescription = 'ages are spread far from the average — indicating high diversity in age';
                        }


                        list.innerHTML = `
                                        <li class="list-group-item">
                                            <strong>Average Age:</strong> The average age of applicants in ${data.year} is <strong>${data.mean}</strong>.
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Median Age:</strong> The typical applicant is around <strong>${data.median}</strong> years old, suggesting most are <strong>${ageGroup}</strong>.
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Most Common Age:</strong> The most frequent age among applicants is <strong>${data.mode}</strong>.
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Age Variance:</strong> ${data.variance} — ${varianceDescription}.
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Standard Deviation:</strong> ~<strong>${data.standard_deviation}</strong> years — ${sdDescription}.
                                        </li>
                                    `;



                        const ctx = document.getElementById('ageStatsChart').getContext('2d');

                        // 🔐 Safe destroy check
                        if (window.ageStatsChart instanceof Chart) {
                            window.ageStatsChart.destroy();
                        }

                        // 🎯 Create chart
                        window.ageStatsChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: ['Mean', 'Median', 'Mode', 'Variance', 'Std Dev'],
                                datasets: [{
                                    label: `Age Statistics(${data.year})`,
                                    data: [
                                        data.mean,
                                        data.median,
                                        data.mode,
                                        data.variance,
                                        data.standard_deviation
                                    ],
                                    backgroundColor: ['#36a2eb', '#ff6384', '#4bc0c0',
                                        '#ff9f40', '#9966ff'
                                    ]
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Value'
                                        }
                                    }
                                }
                            }
                        });

                    })
                    .catch(err => {
                        loadingSpinner.style.display = 'none';
                        document.getElementById('ageStats').innerHTML =
                            `< li class="list-group-item text-danger" > ${err.message}</li >`;
                        console.error('Age stats fetch failed:', err);
                    });

            }

            // now safe to bind
            yearSelector.addEventListener('change', () => {
                fetchAgeStats(yearSelector.value);
            });

            // initial load
            fetchAgeStats(yearSelector.value);
        });
    </script>
@endsection