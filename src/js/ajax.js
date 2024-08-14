function sendAjaxRequest() {

    const xhr = new XMLHttpRequest();
    
    // Add query string parameters to the URL
    const queryString = '?foo=bar&baz=qux';
    const url = '../src/Dashboard.php' + queryString;

    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
                document.getElementById('response').innerText = response.data;
            } else {
                document.getElementById('response').innerText = response.message;
            }
        }
    };

    const name = document.getElementById('name').value;
    const csrfToken = document.getElementById('csrf_token').value;
    const params = `action=getData&name=${encodeURIComponent(name)}&csrf_token=${csrfToken}`;
    xhr.send(params);
}
