<script>
    function fetchSwiftpayOrdersStatistics(url) {
        return fetch(url)
            .then(response => response.json())
            .then(response => response);
    }
</script>
