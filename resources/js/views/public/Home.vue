<template>
    <div>
        <div class="w-full bg-gray-200">
            <div class="w-full pt-4 pb-6 mx-auto">
                <div class="hidden sm:block sm:w-full md:w-2/3 lg:w-1/2 md:mx-auto relative inline-block">
                    <img class="shadow-xl" src="/images/looking_at_plan.jpg" alt="" width="100%">
<!--
                    <img class="shadow-xl" src="/images/looking_at_plan.jpg" alt="" width="100%">
-->
                    <div class="absolute z-40 top-0 mx-auto sm:text-4xl lg:text-5xl xl:text-6xl left-0 right-0 text-center text-indigo-600" style="text-shadow: 2px 2px 5px #ffffff;">
                        Project Management
                    </div>
                    <div class="absolute z-40 mx-auto left-0 right-0 text-center text-gray-700" style="top: 6rem; text-shadow: 2px 2px 5px #ffffff;">
                        The complete toolset for all project managers.
                    </div>
                </div>
                <div class="sm:hidden text-indigo-600 text-center text-2xl" style="text-shadow: 2px 2px 5px #ffffff;">
                    Project Management
                </div>
                <div class="sm:hidden text-gray-700 text-center" style="text-shadow: 2px 2px 5px #ffffff;">
                    The complete toolset for all project managers.
                </div>
            </div>
        </div>
        <div class="w-full mt-2">
            <div class="w-full p-4 flex flex-wrap items-center">
                <div class="w-full flex flex-wrap items-center md:mb-4 md:border-b md:border-gray-100">
                    <div class="w-full md:w-1/2 px-4 py-1 mb-1 text-center md:text-right bg-gray-200 font-bold text-gray-800 rounded-lg">
                        Time keeping
                    </div>
                    <div class="w-full md:w-1/2 px-4 mb-4 md:mb-2 border-b border-gray-100 md:border-0">
                        <p>
                            Tracking time spent on tasks in a project is vital for
                            any project. We allow timesheet entries to be recorded
                            manually or through the help of a timer.
                        </p>
                        <p>
                            Timesheet entries can be assigned to a project or task,
                            but there is also an option to record a timesheet entry
                            for ad-hoc type of work.
                        </p>
                    </div>
                </div>
                <div class="w-full flex flex-wrap items-center md:mb-4 md:border-b md:border-gray-100">
                    <div class="w-full md:w-1/2 px-4 py-1 mb-1 text-center md:text-right bg-gray-200 font-bold text-gray-800 rounded-lg">
                        Project Management
                    </div>
                    <div class="w-full md:w-1/2 px-4 mb-4 border-b border-gray-100 md:border-0">
                        <p>
                            Projects in Project-Management are a set of tasks. A
                            project has a name and you can select a color that is
                            used in timesheet reporting.
                        </p>
                        <p>
                            This will be expanded on in future versions where you
                            can assign projects to a client or a portfolio, assign
                            Project Manager roles, set up project budgets, etc.
                        </p>
                    </div>
                </div>
                <div class="w-full flex flex-wrap items-center md:mb-2">
                    <div class="w-full md:w-1/2 px-4 py-1 mb-1 text-center md:text-right bg-gray-200 font-bold text-gray-800 rounded-lg">
                        Responsive design
                    </div>
                    <div class="w-full md:w-1/2 px-4 mb-4">
                        <p>
                            Using tailwindcss, Project-Management is designed from
                            the ground up to suit any device.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Home"
    }
</script>

<style scoped>

</style>
