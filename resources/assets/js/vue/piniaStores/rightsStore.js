import { defineStore } from 'pinia'
import axios from 'axios'

export const useRightsStore = defineStore('rights', {
    state: () => ({
        profileRights: null,
        error: null,
    }),
    actions: {
        async fetchRights() {
            if (!this.profileRights && !this.error) {
                try {
                    const response = await axios.get('/api/profileRights/all')
                    if (response.status === 200) {
                        this.profileRights = response.data
                    } else {
                        console.error(`Failed to fetch profile rights: ${response.status}`)
                        this.error = `Error fetching profile rights: ${response.status}`
                        this.profileRights = null
                        throw new Error(this.error)
                    }
                } catch (error) {
                    if (error.status === 401) {
                        this.profileRights = null
                        console.warn(error)
                    }
                }
            }

            return this.profileRights
        },
        async updateRight(profileId, rightId, newValue){
            const profile = this.profileRights.find(profile => profile.id === profileId);

            if (profile) {
                const right = profile.rights.find(right => right.id === rightId);

                if (right) {
                    right.right_assigned = newValue;
                } else {
                    console.error("Right not found in profile:", profileId, rightId);
                }
            } else {
                console.error("Profile not found:", profileId);
            }
        }
    },
    getters: {
        getRights() {
            return this.profileRights
        },
    },
})
