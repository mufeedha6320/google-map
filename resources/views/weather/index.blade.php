<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <title>Weather Information</title>
</head>
<body>
    <div class="container p-5">
        <div class="row">
            <div class="col-md-4">
                <form id="weather-form" method="GET">
                    <label for="city">Enter City Name:</label>
                    {{-- <input type="text" id="city" name="city" required> --}}
                    <div class="form-group">
                        <select id="city" name="city" >
                            <option value="" disabled selected>Choose city</option>
                            <?php foreach ($names as $name): ?>
                                <option value="<?php echo htmlspecialchars($name); ?>"><?php echo htmlspecialchars($name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit">Get Weather</button>
                </form>
            </div>
            <div class="col-md-8" id="result">
            </div>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>


<script>
    $(document).ready(function() {
        $('#weather-form').on('submit', function(e) {
            console.log("form submission");

            e.preventDefault(); // Prevent the form from submitting normally

            var city = $('#city').val();
            $.ajax({
                url: "{{ url('api/weather') }}",
                type: 'GET',
                data: { city: city },
                success: function(data) {
                    var resultHtml = '<table class="table table-dark">';
                    resultHtml += '<thead><tr><th>Parameter</th><th>Value</th></tr></thead><tbody>';
                    resultHtml += '<tr><td>Longitude</td><td>' + data.coord.lon + '</td></tr>';
                    resultHtml += '<tr><td>Latitude</td><td>' + data.coord.lat + '</td></tr>';
                    resultHtml += '<tr><td>Weather</td><td>' + data.weather[0].main + ' (' + data.weather[0].description + ')</td></tr>';
                    resultHtml += '<tr><td>Temperature (°C)</td><td>' + data.main.temp + '</td></tr>';
                    resultHtml += '<tr><td>Feels Like (°C)</td><td>' + data.main.feels_like + '</td></tr>';
                    resultHtml += '<tr><td>Minimum Temperature (°C)</td><td>' + data.main.temp_min + '</td></tr>';
                    resultHtml += '<tr><td>Maximum Temperature (°C)</td><td>' + data.main.temp_max + '</td></tr>';
                    resultHtml += '<tr><td>Pressure (hPa)</td><td>' + data.main.pressure + '</td></tr>';
                    resultHtml += '<tr><td>Humidity (%)</td><td>' + data.main.humidity + '</td></tr>';
                    resultHtml += '<tr><td>Sea Level Pressure (hPa)</td><td>' + (data.main.sea_level || 'N/A') + '</td></tr>';
                    resultHtml += '<tr><td>Ground Level Pressure (hPa)</td><td>' + (data.main.grnd_level || 'N/A') + '</td></tr>';
                    resultHtml += '</tbody></table>';

                    $('#result').html(resultHtml); // Update the result div with the new content
                },
                error: function(xhr) {
                    $('#result').html('<p>Error retrieving weather data. Please try again.</p>');
                }
            });
        });
    });
</script>
</html>
