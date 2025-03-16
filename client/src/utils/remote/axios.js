import axios from "axios";

axios.defaults.baseURL = "http://localhost/Photo-Gallery/server/api/v1"; 
axios.defaults.withCredentials = true;
axios.defaults.headers = {
  "Content-Type": "application/json",
};

export const request = async ({ method, route, body, headers }) => {
  try {
    const response = await axios.request({
      method, 
      headers:{
        ...headers,
      },
      url: route,
      data: body,
    });

    return response.data;
  } catch (error) {
    console.error("API Request Error:", error); 
    return {
      error: true,
      message: error.response ? error.response.data : error.message,
    };
  }
};
