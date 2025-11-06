// create-app-user.js
// executed as root by official mongo image during init (only when DB empty)
db = db.getSiblingDB("admin");

// create root (only if not exists)
try {
  db.createUser({
    user: "root",
    pwd: "rootsecret123",
    roles: [{ role: "root", db: "admin" }]
  });
} catch(e) {
  // ignore if exists
  print("root user create skipped: "+e);
}

// create application user in mcu_db
const appDb = db.getSiblingDB("mcu_db");
try {
  appDb.createUser({
    user: "mcuapp",
    pwd: "mcuappsecret",
    roles: [
      { role: "readWrite", db: "mcu_db" },
      { role: "read", db: "admin" }
    ]
  });
} catch(e) {
  print("app user create skipped: "+e);
}
