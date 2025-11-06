// docker/mongo-initdb/create-app-user.js
// runs once on first start if DB is empty
const adminDB = db.getSiblingDB('admin');

// optional: ensure root user exists (entrypoint will create root automatically when MONGO_INITDB_ROOT_* set)
// create an app user with readWrite on mcu_db:
const appDb = db.getSiblingDB('mcu_db');

appDb.createUser({
  user: "mcu_user",
  pwd: "AkjUL69420__@!",
  roles: [
    { role: "readWrite", db: "mcu_db" }
  ]
});

print('Created app user appuser for DB mcu_db');
