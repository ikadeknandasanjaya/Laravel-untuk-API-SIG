import axios from 'axios';

const API_URL = "https://api.frankfurter.app/currencies"

class CurrencyService {
    
    async getAllCurrencies(){
        try {
            const response = await axios.get(API_URL);

            const currencies = Object.entries(response.data).map(([code, name]) => ({
                id: code,
                jenis_mata_uang: `${code} - ${name}`
            }));

            return {
                success: true,
                data: currencies
            };
        } catch (error) {
            console.error('Error fetching currencies:', error);
            return {
                success: false,
                message: error.response?.data?.message || error.message || 'Failed to fetch currencies'
            };
        }
    }
}

export default new CurrencyService();