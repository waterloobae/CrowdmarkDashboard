function sendAjaxRequest(event, action, formId) {
    // Prevent the default form submission
    event.preventDefault();
    const xhr = new XMLHttpRequest();
    
    // Construct the URL with the query string
    const url = `../src/AjaxHandler.php`;

    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            alert(response.status);
            if (response.status === 'success') {
                //document.getElementById('response').innerText = response.data;
                document.getElementById('response').innerText = response;                
            } else {
                //document.getElementById('response').innerText = response.message;
                alert(response.message);
                document.getElementById('response').innerText = response;
            }
        }
    };

     // Get the form by its ID, which is passed as a parameter
     const form = document.getElementById(formId);
     if (!form) {
         console.error(`Form with id "${formId}" not found.`);
         return;
     } 
     const formData = new FormData(form);
 
     // Append the action parameter to the form data
     formData.append('action', action);
 
     // Convert FormData to URL-encoded string for the POST request
     const params = new URLSearchParams(formData).toString();
     // alert(params);
    xhr.send(params);
}

// Example call to the function with action and form ID as parameters
// sendAjaxRequest('getData', 'myForm');

