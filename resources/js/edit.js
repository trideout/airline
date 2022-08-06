export default ()  => ({
    point: point,
    originalPoint: {...point},
    points: points,
    closestDistance: '',
    farthestDistance: '',
    formErrors: [],
    get furthestPoints() {
        this.points.forEach(point => {
            point.distance = this.calculateDistance(point.x, point.y, this.point.x, this.point.y);
        });
        this.farthestDistance = this.points.reduce((prev, curr) => {
            return prev.distance > curr.distance ? prev : curr;
        }, 0).distance;
        return this.points.filter(point => point.distance === this.farthestDistance);
        },
    get closestPoints() {
        this.points.forEach(point => {
            point.distance = this.calculateDistance(point.x, point.y, this.point.x, this.point.y);
        });
        this.closestDistance = this.points.reduce((prev, curr) => {
            return prev.distance < curr.distance ? prev : curr;
        }, 0).distance;
        return this.points.filter(point => point.distance === this.closestDistance);
    },
    get farthestPointText() {
        if (this.furthestPoints.length === 0) {
            return 'No farthest points found';
        }
        if (this.furthestPoints.length > 1) {
            return `The farthest points are ${Math.round(this.farthestDistance * 10)/10} units away.`;
        }
        return `The farthest point is ${Math.round(this.farthestDistance * 10)/10} units away.`;
    },
    get closestPointText() {
        if (this.closestPoints.length === 0) {
            return 'No closest points found';
        }
        if (this.closestPoints.length > 1) {
            return `The closest points are ${Math.round(this.closestDistance * 10)/10} units away.`;
        }
        return `The closest point is ${Math.round(this.closestDistance * 10)/10} units away.`;
    },
    calculateDistance(x1, y1, x2, y2) {
        return Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2));
    },
    inputHasErrors(inputName) {
        if (this.formErrors && this.formErrors.errors && this.formErrors.errors[inputName]){
            return 'error';
        }
        return '';
    },
    async submitForm() {
        console.log(this.point);
        let uri = this.point.id  ? `/points/${this.point.id}` : '/points';
        let response = await fetch(uri, {
            method: this.point.id ? 'PUT' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                name: this.point.name,
                x: this.point.x,
                y: this.point.y
            })
        });
        let data = await response.json();
        if (response.status === 422) {
            this.formErrors = data;
            return;
        }
        if (response.ok) {
            this.$dispatch('form-saved', data);
            return;
        }
    },
    returnToList() {
        window.location.href = '/points';
    },
    get isDirty() {
        return !(this.point.name != this.originalPoint.name || this.point.x != this.originalPoint.x || this.point.y != this.originalPoint.y);
    },
});
