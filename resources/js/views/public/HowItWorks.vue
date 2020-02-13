<template>
    <div class="w-full mt-2">
        <div class="w-full p-4 flex flex-wrap items-center">
            <div class="w-full flex flex-wrap items-center md:mb-4 md:border-b md:border-gray-100">
                <div class="w-full md:w-1/2 px-4 py-1 mb-1 text-center md:text-right bg-gray-200 font-bold text-gray-800 rounded-lg">
                    1. Workspaces
                </div>
                <div class="w-full md:w-1/2 px-4 mb-4 md:mb-2 border-b border-gray-100 md:border-0">
                    <p>
                        Workspaces are collections of projects and users. When
                        you register a Project-Management account, a workspace
                        called "Default" will be created for you.
                    </p>
<!--
                    <p>
                        You can invite new and existing users to your workspace.
                        If the person you are inviting does not have a
                        Project-Management account, they will receive an email
                        indicating that you invited them to join. If the person
                        you are inviting already has a Project-Management
                        account, they will receive a notification that they have
                        been invited to join an new workspace.
                    </p>
-->
                </div>
            </div>
            <div class="w-full flex flex-wrap items-center md:mb-4 md:border-b md:border-gray-100">
                <div class="w-full md:w-1/2 px-4 py-1 mb-1 text-center md:text-right bg-gray-200 font-bold text-gray-800 rounded-lg">
                    2. Projects and tasks
                </div>
                <div class="w-full md:w-1/2 px-4 mb-4 border-b border-gray-100 md:border-0">
                    <p>
                        You can add projects to your workspace by clicking the
                        projects menu and then clicking "Add project".
                        This will ask you to chose a name for the project, and
                        pick a color that will be associated with the project.
                    </p>
                    <p>
                        You can then click into the project to add tasks to that
                        project. Currently, the tasks are just a collection, but
                        that will change in the near future.
                    </p>
                </div>
            </div>
            <div class="w-full flex flex-wrap items-center md:mb-2">
                <div class="w-full md:w-1/2 px-4 py-1 mb-1 text-center md:text-right bg-gray-200 font-bold text-gray-800 rounded-lg">
                    3. Timesheets
                </div>
                <div class="w-full md:w-1/2 px-4 mb-4">
                    <p>
                        You can record timesheet entries manually or using the
                        timer. Incorrect timesheet entries can be altered or
                        deleted.
                    </p>
                    <p>
                        When looking at a timesheet report, you can easily
                        distinguish the amount of time spent on each project,
                        or the amount spent on each task within a project.
                    </p>
                    <p>
                        You can export your timesheet entries to a
                        Microsoft&reg; Excel&trade; file for further analysis,
                        generating invoices or sharing with your colleagues.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "HowItWorks"
    }
</script>

<style scoped>

</style>
