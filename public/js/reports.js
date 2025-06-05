document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('donationsChart').getContext('2d');
        
        // Create gradient for chart
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(75, 192, 192, 0.6)');
        gradient.addColorStop(1, 'rgba(75, 192, 192, 0.1)');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($donationWeeks ?? []) !!},
                datasets: [{
                    label: 'Donations (₱)',
                    data: {!! json_encode($donationAmounts ?? []) !!},
                    backgroundColor: gradient,
                    borderColor: 'rgb(75, 192, 192)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += '₱' + new Intl.NumberFormat().format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush    