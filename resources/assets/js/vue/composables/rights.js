import axios from "axios";
import {useUserStore} from "../piniaStores/userStore";
import {useRightsStore} from "../piniaStores/rightsStore";

/**
 * Check if the user has any of the specified permissions.
 *
 * @param {Array} rights Array of rights to check
 * @returns {Promise<boolean>} A promise that resolves to true if the user has any of the specified permissions, false otherwise
 */
export function hasRight(rights){
    return new Promise(async (resolve, reject) => {
        if (await useRightsStore().getRights === null) {
            await useRightsStore().fetchRights()
        }

        if (await useUserStore().getUser === null) {
            await useUserStore().fetchUser()
        }

        const profileRights = await useRightsStore().getRights
        const userProfile = parseInt(useUserStore().getUser.profile_id)


        const profile = profileRights.find(profile => profile.id === userProfile);
        let rightValues = [];

        if (profile) {
            for (const checkRight of rights) {
                const discoveredRight = profile.rights.find(right => right.name === checkRight);

                rightValues[discoveredRight.name] = discoveredRight.right_assigned;
            }
        } else {
            reject(`Could not find the profile with ID ${userProfile}.`)
        }

        resolve(rightValues);
    })
}
