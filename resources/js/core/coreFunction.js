import { ref, database, set,onDisconnect } from '../firebase';

export function useUserStatus(userId) {
    const userStatusRef = ref(database, `status/${userId}`);
    set(userStatusRef, {
        user_id:userId,
        state: 'online',
        last_changed: Date.now()
    });
    onDisconnect(userStatusRef).set({
        user_id:userId,
        state: 'offline',
        last_changed: Date.now()
    }).then(() => {
        console.log('Disconnect handler set successfully');
    }).catch((error) => {
        console.error('Error setting disconnect handler:', error);
    });
    return {
        userStatusRef
    };
}
