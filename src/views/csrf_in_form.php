<script>
    // Fetch CSRF token on page load
    fetch('../src/AjaxHandler.php?csrf=true')
      .then(response => response.json())
      .then(data => {
          document.getElementById('csrf_token').value = data.csrf_token;
      });
    document.getElementById('crowdmarkDashboardFrom').addEventListener('submit', function (event) {
      createHiddenControl();
      sendAjaxRequest(event, 'sayHello', 'crowdmarkDashboardFrom');
      deleteHiddenControl()
    });
    function createHiddenControl() {
      const form = document.getElementById("crowdmarkDashboardFrom");
      const chips = document.querySelectorAll("md-filter-chip");
      const selectedChips = [];
      chips.forEach(chip => {
          const chipValue = chip.getAttribute("label");
          //alert(chip.selected);              
          //alert(chip.getAttribute("selected"));
          const isSelected = chip.selected;
      
          if (isSelected) {
              // Add selected chip to the array
              selectedChips.push(chipValue);
          }
      }); // Added missing parenthesis
    
      const hiddenControl = document.createElement('input');
      hiddenControl.type = 'hidden';
      hiddenControl.name = 'selectedChips';
      hiddenControl.value = JSON.stringify(selectedChips);  // Post as JSON array
      alert(hiddenControl.value);
      form.appendChild(hiddenControl);
    }
    
    function deleteHiddenControl() {
      const form = document.getElementById("crowdmarkDashboardFrom");
      const hiddenControl = form.querySelector('input[name="selectedChips"]');
      if (hiddenControl) {
          form.removeChild(hiddenControl);
      }
    }
</script>