// api.js
export const fetchData = async (endpoint, nonce) => {
    try {
        const response = await fetch(endpoint, {
            method: 'GET',
            headers: {
                Accept: 'application/json',
                'X-WP-Nonce': nonce,
            },
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching data:', error);
        throw error;
    }
};

export const saveData = async (endpoint, nonce, payload) => {
    try {
        const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': nonce,
            },
            body: JSON.stringify({ settings: payload }),
        });

        if (!response.ok) {
            return response.json();
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        return data;
    } catch (error) {
        return error;
        console.error('Error saving data:', error);
        throw error;
    }
};
