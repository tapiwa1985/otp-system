import axios from "axios";

export default axios.create({
    baseURL: "http://thomas-kangai.php.test.entrostat.xyz:8080/api/",
    headers: {
        "Content-type": "application/json"
    }
});