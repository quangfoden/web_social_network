import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
import {
    getDatabase,
    ref,
    push,
    onValue,
    query,
    orderByChild,
    equalTo,
    limitToLast,
    set,
    get,
    onDisconnect,
} from "firebase/database";
const firebaseConfig = {
    apiKey: "AIzaSyATs7_dZVhtF5ODiV_cWVWLoPwMuNh6txk",
    authDomain: "mangxahoi-b7499.firebaseapp.com",
    databaseURL: "https://mangxahoi-b7499-default-rtdb.firebaseio.com",
    projectId: "mangxahoi-b7499",
    storageBucket: "mangxahoi-b7499.appspot.com",
    messagingSenderId: "122651574808",
    appId: "1:122651574808:web:89bee18e456692b8ecb96d",
    measurementId: "G-V5JHB9DPEN"
};

const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
const database = getDatabase();
export {
    database,
    ref,
    push,
    onValue,
    query,
    orderByChild,
    equalTo,
    limitToLast,
    set,
    onDisconnect,
    get
};
