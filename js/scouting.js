// Author: Trevor Goff
// Date: Apr 2-19
// Description: Send form data from scouting page to PHP backend.
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('scoutForm');
    const submitButton = form.querySelector('button[type="submit"]');

    let status = document.getElementById('formStatus');
    if (!status) {
        status = document.createElement('div');
        status.id = 'formStatus';
        status.style.marginTop = '10px';
        form.appendChild(status);
    }
    // On submit, format data to a json and send in a POST request to the backend.
    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const formData = new FormData(form);

        const data = {
            matchNumber: formData.get('matchNumber'),
            matchType: formData.get('matchType'),
            allianceColor: formData.get('allianceColor'),
            predictWin: formData.get('predictWin'),
            actualWin: formData.get('actualWin'),
            rankingPoints: formData.get('rankingPoints'),
            opponents: formData.get('opponents'),
            details: formData.get('details'),
            team: {
                number: formData.get('teams[0][number]'),
                path: formData.get('teams[0][path]'),
                disabled: formData.get('teams[0][disabled]'),
                capabilities: formData.getAll('teams[0][capabilities][]')
            }
        };

        submitButton.disabled = true;
        const originalText = submitButton.textContent;
        submitButton.textContent = 'Submitting...';

        status.textContent = 'Submitting data...';
        status.style.color = 'black';

        try {
            const response = await fetch('./php/submit.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.error || 'Submission failed');
            }

            status.textContent = 'Saved successfully.';
            status.style.color = 'green';

            form.reset();

        } catch (error) {
            status.textContent = 'Error: ' + error.message;
            status.style.color = 'red';
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    });
});