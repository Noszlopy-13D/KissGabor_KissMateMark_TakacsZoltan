const { app, BrowserWindow, ipcMain } = require("electron");
const path = require("path");
const axios = require("axios");

function createWindow() {
    const win = new BrowserWindow({
        width: 800,
        height: 680,
        minWidth: 800,
        minHeight: 680,
        webPreferences: {
            nodeIntegration: true,
            contextIsolation: false,
        },
        icon: path.join(__dirname, 'resources', 'icon.png')
    });

    win.loadFile("login.html");
}

app.whenReady().then(createWindow);

// Helyi rendelések listája
let localOrders = [
    { id: "9", order_date: "1731350651", order_status: "2", total_price: "43806" },
    { id: "10", order_date: "1731350733", order_status: "1", total_price: "37118" },
    { id: "11", order_date: "1733771977", order_status: "1", total_price: "43801"}
];

const localUsers = [
    { email: "admin@gmail.com", password: "1234" }
];

// Segédfunkciók API kommunikációhoz
async function getServerData(endpoint) {
    try {
        const response = await axios.get(endpoint);
        return response.data.data;
    } catch (error) {
        if (error.code === 'ECONNREFUSED' || !error.response) {
            return null;
        }
        console.error("Unexpected error in getServerData:", error);
        return null;
    }
}

async function postServerData(endpoint, data) {
    try {
        const response = await axios.post(endpoint, data, { headers: { "Content-Type": "application/json" } });
        return response.data;
    } catch (error) {
        if (error.code === 'ECONNREFUSED' || !error.response) {
            return null;
        }
        console.error("Unexpected error in postServerData:", error);
        return null;
    }
}

// **RENDELÉSEK LEKÉRÉSE**
ipcMain.handle("get-orders", async () => {
    const ordersFromServer = await getServerData("http://localhost/api.php/orders");
    if (ordersFromServer) {
        return ordersFromServer;
    } else {
        console.log("Szerver nem elérhető, helyi rendeléseket használunk.");
        return [...localOrders]; // Helyi fájl
    }
});

// **RENDELÉS STÁTUSZ MÓDOSÍTÁSA**
ipcMain.handle("update-order", async (event, orderId, status) => {
    const result = await getServerData("http://localhost/api.php/orders");
    
    if (result) {
        const data = { order_id: orderId, order_status: status };
        const updateResult = await postServerData("http://localhost/api.php/orders", data);
        return updateResult;
    } else {
        // Alert helyett visszatérés az üzenettel
        return { success: false, message: "Szerver nem elérhető, nem lehet frissíteni a rendelést." };
    }
});

// **RENDELÉS TÖRLÉSE**
ipcMain.handle("delete-order", async (event, orderId) => {
    const result = await getServerData("http://localhost/api.php/orders");
    
    if (result) {
        const data = { order_id: orderId, delete_order: true };
        const deleteResult = await postServerData("http://localhost/api.php/orders", data);
        return deleteResult;
    } else {
        // Alert helyett visszatérés az üzenettel
        return { success: false, message: "Szerver nem elérhető, nem lehet törölni a rendelést." };
    }
});

// **BEJELENTKEZÉS**
ipcMain.handle("login", async (event, email, password) => {
    const result = await postServerData("http://localhost/api.php/login", { email, password });
    
    if (result) {
        return result;
    } else {
        const user = localUsers.find(user => user.email === email && user.password === password);
        if (user) {
            return { success: true, message: "Sikeres bejelentkezés!" };
        } else {
            return { success: false, message: "Hibás felhasználónév vagy jelszó." };
        }
    }
});


app.on("window-all-closed", () => {
    if (process.platform !== "darwin") {
        app.quit();
    }
});
